<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Package;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Enum\PackageStatus;
use App\Form\OrderFormType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_order')]
    public function index(OrderRepository $repository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('order/index.html.twig', [
                'orders' => $repository->findAllDetailed(),
            ]);
        }

        if ($this->isGranted('ROLE_BUSINESS')) {
            return $this->redirectToRoute('app_order_business');
        }

        if ($this->isGranted('ROLE_CONSUMER')) {
            return $this->redirectToRoute('app_order_my');
        }

        return $this->redirectToRoute('app_package');
    }

    #[Route('/orders/my', name: 'app_order_my')]
    public function myOrders(OrderRepository $repository, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONSUMER');

        $user = $security->getUser();
        if (!$user || !$user->getConsumer()) {
            $this->addFlash('error', 'Your account has no linked customer profile.');
            return $this->redirectToRoute('app_package');
        }

        return $this->render('order/my.html.twig', [
            'orders' => $repository->findByConsumer($user->getConsumer()),
        ]);
    }

    #[Route('/orders/business', name: 'app_order_business')]
    public function businessOrders(OrderRepository $repository, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BUSINESS');

        $user = $security->getUser();
        if (!$user || !$user->getBusiness()) {
            $this->addFlash('error', 'Your account has no linked business profile.');
            return $this->redirectToRoute('app_package');
        }

        return $this->render('order/business.html.twig', [
            'orders' => $repository->findByBusiness($user->getBusiness()),
        ]);
    }

    #[Route('/orders/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_view', ['id' => $order->getId()]);
        }

        return $this->render('order/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/packages/{id}/order', name: 'app_order_create_from_package', methods: ['POST'])]
    public function createFromPackage(Package $package, Security $security, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONSUMER');

        $user = $security->getUser();
        if (!$user || !$user->getConsumer()) {
            $this->addFlash('error', 'Your account has no linked customer profile.');
            return $this->redirectToRoute('app_package');
        }

        if ($package->getStatus() !== PackageStatus::AVAILABLE->value) {
            $this->addFlash('warning', 'This package is no longer available.');
            return $this->redirectToRoute('app_package_view', ['id' => $package->getId()]);
        }

        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setPackage($package);
        $order->setConsumer($user->getConsumer());
        $order->setStatus(OrderStatus::PLACED->value);

        $package->setStatus(PackageStatus::RESERVED->value);

        $entityManager->persist($order);
        $entityManager->flush();

        $this->addFlash('success', 'Transaction completed. Your order was placed.');
        return $this->redirectToRoute('app_order_my');
    }

    #[Route('/orders/{id}', name: 'app_order_view', requirements: ['id' => '\d+'])]
    public function view(Order $order, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user || !$this->canAccessOrder($user, $order)) {
            return $this->redirectToRoute('app_package');
        }

        return $this->render('order/view.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/orders/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_view', ['id' => $order->getId()]);
        }

        return $this->render('order/edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[Route('/orders/{id}/cancel', name: 'app_order_cancel', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function cancel(Order $order, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user || !$this->canCancelOrder($user, $order)) {
            return $this->redirectToRoute('app_package');
        }

        $package = $order->getPackage();
        if ($package) {
            $package->setStatus(PackageStatus::AVAILABLE->value);
        }

        $entityManager->remove($order);
        $entityManager->flush();

        $this->addFlash('success', 'Your order has been cancelled.');

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('app_order');
        }

        return $this->redirectToRoute('app_order_my');
    }

    #[Route('/orders/{id}/complete', name: 'app_order_complete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function complete(Order $order, EntityManagerInterface $entityManager, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BUSINESS');

        $user = $security->getUser();
        if (
            !$user
            || !$user->getBusiness()
            || !$order->getPackage()
            || !$order->getPackage()->getBusiness()
            || $user->getBusiness()->getId() !== $order->getPackage()->getBusiness()->getId()
        ) {
            $this->addFlash('error', 'You are not authorised to complete this order.');
            return $this->redirectToRoute('app_order_business');
        }

        $order->setStatus(OrderStatus::COMPLETED->value);
        $order->getPackage()->setStatus(PackageStatus::SOLD->value);

        $entityManager->flush();

        $this->addFlash('success', 'Order marked as picked up.');
        return $this->redirectToRoute('app_order_business');
    }

    private function canAccessOrder(User $user, Order $order): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        if (
            in_array('ROLE_CONSUMER', $user->getRoles(), true)
            && $user->getConsumer()
            && $order->getConsumer()
            && $user->getConsumer()->getId() === $order->getConsumer()->getId()
        ) {
            return true;
        }

        return in_array('ROLE_BUSINESS', $user->getRoles(), true)
            && $user->getBusiness()
            && $order->getPackage()
            && $order->getPackage()->getBusiness()
            && $user->getBusiness()->getId() === $order->getPackage()->getBusiness()->getId();
    }

    private function canCancelOrder(User $user, Order $order): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        return in_array('ROLE_CONSUMER', $user->getRoles(), true)
            && $user->getConsumer()
            && $order->getConsumer()
            && $user->getConsumer()->getId() === $order->getConsumer()->getId();
    }
}
