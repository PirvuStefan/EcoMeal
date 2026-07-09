<?php

namespace App\Controller;

use App\Entity\Consumer;
use App\Form\ConsumerFormType;
use App\Repository\ConsumerRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ConsumerController extends AbstractController
{


    #[Route('/customers/{id}', name: 'app_consumer_view')]
    public function view(Consumer $consumer, OrderRepository $orderRepository, Security $security): Response
    {

        $user = $security->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_package');
        }
        $roles = $user->getRoles();
        if($user->getId() != $consumer->getId() or $roles == 'ROLE_BUSINESS') {
            // adminul si doar userul de tip consumer cu id ul lui poate vedea aceasta pagina ig
            return $this->redirectToRoute('app_package');
        }
        $orders = $orderRepository->findBy(['consumer' => $consumer]);
        return $this->render('consumer/view.html.twig', [
            'consumer' => $consumer,
            'orders' => $orders,
        ]);
    }

    #[Route('/customers/new', name: 'app_consumer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consumer = new Consumer();
        $form = $this->createForm(ConsumerFormType::class, $consumer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($consumer);
            $entityManager->flush();

            return $this->redirectToRoute('app_consumer');
        }

        return $this->render('consumer/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/customers/{id}/edit', name: 'app_consumer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consumer $consumer, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(ConsumerFormType::class, $consumer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consumer');
        }

        return $this->render('consumer/edit.html.twig', [
            'form' => $form,
            'consumer' => $consumer,
        ]);
    }

    #[Route('/customers/{id}/delete', name: 'app_consumer_delete', methods: ['GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $consumer = $entityManager->find(Consumer::class, $id);

        if ($consumer === null) {
            return $this->redirectToRoute('app_consumer');
        }

        $entityManager->remove($consumer);
        $entityManager->flush();

        return $this->redirectToRoute('app_consumer');
    }

    #[Route('/customers', name: 'app_consumer')]
    public function index(ConsumerRepository $repository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $consumers = $repository->findAll();
        return $this->render('consumer/index.html.twig', [
            'consumers' => $consumers,
        ]);
    }


}
