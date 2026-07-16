<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorite')]
    public function index(FavoriteRepository $repository, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONSUMER');

        $favorites = $repository->findBy(['user' => $security->getUser()]);

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/business/{id}/favorite', name: 'app_favorite_add', methods: ['POST'])]
    public function add(Business $business, Request $request, FavoriteRepository $repository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONSUMER');

        $user = $security->getUser();

        if (!$repository->findOneByUserAndBusiness($user, $business)) {
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setBusiness($business);
            $favorite->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($favorite);
            $entityManager->flush();
        }

        $this->addFlash('success', sprintf('%s added to your favorites.', $business->getName()));

        return $this->redirectBack($request);
    }

    #[Route('/business/{id}/unfavorite', name: 'app_favorite_remove', methods: ['POST'])]
    public function remove(Business $business, Request $request, FavoriteRepository $repository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONSUMER');

        $user = $security->getUser();
        $favorite = $repository->findOneByUserAndBusiness($user, $business);

        if ($favorite) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        $this->addFlash('success', sprintf('%s removed from your favorites.', $business->getName()));

        return $this->redirectBack($request);
    }

    private function redirectBack(Request $request): Response
    {
        $referer = $request->headers->get('referer');

        if ($referer && str_starts_with($referer, $request->getSchemeAndHttpHost())) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_favorite');
    }
}
