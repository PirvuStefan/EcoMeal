<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\BusinessType;
use App\Form\BusinessFormType;
use App\Repository\BusinessRepository;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BusinessController extends AbstractController
{
    #[Route('/business', name: 'app_business')]
    public function index(BusinessRepository $repository): Response
    {

        $businesses = $repository->findAll();
        return $this->render('business/index.html.twig', [
            'businesses' => $businesses,
        ]);
    }

    #[Route('/business/{id}', name: 'app_business_view')]
    public function view(Business $business, EntityManagerInterface $entityManager, PackageRepository $repositoryCall): Response
    {
        //$business = $repository->find($id);
        $package = $repositoryCall->findBy(['business' => $business]);
        return $this->render('business/view.html.twig', [
            'business' => $business,
            'packages' => $package, // Pass the found package to the template
        ]);
    }

    #[Route('/new/business', name: 'app_business_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response{
        $business = new Business();
        $form = $this->createForm(BusinessFormType::class, $business);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($business);
            $entityManager->flush();
        }

        return $this->render('business/new.html.twig', [
            'form' => $form,
        ]);
    }
}
