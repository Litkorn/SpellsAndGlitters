<?php

namespace App\Controller\Creations;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/creations', name: 'app_creations_')]
class CreationsController extends AbstractController
{
    /* Creations list (all) */
    #[Route('/all', name: 'all')]
    public function index(ItemRepository $itemRepo): Response
    {
        $creations = $itemRepo->findBy(['isActive' => true], ['createdAt' => 'DESC']);

        return $this->render('Creations/index.html.twig', [
            'creations'         => $creations
        ]);
    }

    /* Creation details */
    // #[Route('/{slug}', name: 'details')]

    /* Routes to handle favorites */
    #[IsGranted('ROLE_USER')]
    #[Route('/favoris/ajout/{id}', name: 'add_favorite')]
    public function addFavoris(Item $item, EntityManagerInterface $em)
    {
        if(!$item){
            throw new NotFoundHttpException('Pas de création trouvée');
        }
        $item->addFavorite($this->getUser());
        $em->persist($item);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/favoris/suppression/{id}', name: 'remove_favorite')]
    public function removeFavoris(Item $item, EntityManagerInterface $em)
    {
        if(!$item){
            throw new NotFoundHttpException('Pas de création trouvée');
        }
        $item->removeFavorite($this->getUser());
        $em->persist($item);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
