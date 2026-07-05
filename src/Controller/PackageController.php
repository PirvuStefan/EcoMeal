<?php

namespace App\Controller;

use App\Entity\Package;
use App\Form\PackageFormType;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PackageController extends AbstractController
{
    #[Route('/packages', name: 'app_package')]
    public function index(PackageRepository $repository): Response
    {
        $packages = $repository->findAll();
        return $this->render('package/index.html.twig', [
            'packages' => $packages,
        ]);
    }

    #[Route('/packages/{id}', name: 'app_package_view')]
    public function view(Package $package): Response
    {
        return $this->render('package/view.html.twig', [
            'package' => $package,
        ]);
    }

    #[Route('/new/package', name: 'app_package_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $package = new Package();
        $form = $this->createForm(PackageFormType::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $package->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($package);
            $entityManager->flush();

            return $this->redirectToRoute('app_package');
        }

        return $this->render('package/new.html.twig', [
            'form' => $form,
        ]);
    }
}
