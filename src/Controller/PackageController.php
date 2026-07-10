<?php

namespace App\Controller;

use App\Dto\PackageSearchFilter;
use App\Entity\Business;
use App\Entity\BusinessType;
use App\Entity\Package;
use App\Form\PackageFiltersType;
use App\Form\PackageFormType;
use App\Repository\BusinessRepository;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isNull;

final class PackageController extends AbstractController
{
    #[Route('/packages', name: 'app_package')]
    public function index(Request $request, PackageRepository $repository): Response
    {

        $filter = new PackageSearchFilter();
        $form = $this->createForm(PackageFiltersType::class, $filter);
        $form->handleRequest($request);

        return $this->render('package/index.html.twig', [
            'packages' => $repository->findByFilter($filter),
            'package_filter_form' => $form->createView(),
        ]);
    }

    #[Route('/packages/{id}', name: 'app_package_view')]
    public function view(Package $package): Response
    {

        return $this->render('package/view.html.twig', [
            'package' => $package,
        ]);
    }

    #[Route('/business/{id}/new/package', name: 'app_package_new', methods: ['GET','POST'])]
    public function new(Request $request, int $id, EntityManagerInterface $entityManager, BusinessRepository $repositoryCall, Security $security): Response
    {

        $this->denyAccessUnlessGranted('ROLE_BUSINESS');
        $user = $security->getUser();

        if(!$user) {
            return $this->redirectToRoute('app_package');
        }

        if($user->getId() !== $id) {

            return $this->redirectToRoute('app_package');

        }

        $package = new Package();
        $business = $repositoryCall->find($id);
        $package->setBusiness($business);
        $form = $this->createForm(PackageFormType::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $package->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($package);
            $entityManager->flush();



        }

        return $this->render('package/new.html.twig', [
            'form' => $form,
        ]);
    }

    //update a package

    #[Route('/packages/{id}/edit', name: 'app_package_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Package $package, EntityManagerInterface $entityManager, Security $security): Response
    {
        $form = $this->createForm(PackageFormType::class, $package);
        $form->handleRequest($request);

        $user = $security->getUser();



        $this->denyAccessUnlessGranted('ROLE_BUSINESS');


        if($user->getBusiness()->getId() !== $package->getBusiness()->getId()) {
            return $this->redirectToRoute('app_package');
        }// doar daca detin pachetul pot sa il editez


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_package');
        }

        return $this->render('package/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/packages/{id}/delete', name: 'app_package_delete', methods: ['GET'])]
    public function delete(Request $request, Package $package, EntityManagerInterface $entityManager, Security $security): Response
    {


        if(is_null($package)){
            return $this->redirectToRoute('app_business');
        }


        $this->denyAccessUnlessGranted('ROLE_BUSINESS');

        $user = $security->getUser();


        if($user->getBusiness()->getId() !== $package->getBusiness()->getId()) {
            return $this->redirectToRoute('app_package');
        }// doar daca detin pachetul pot sa il sterg


            $entityManager->remove($package);
            $entityManager->flush();


        return $this->redirectToRoute('app_package');
    }
}
