<?php

namespace App\Controller\Creations;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CreationsController extends AbstractController
{
    #[Route('/creations', name: 'app_creations')]
    public function index(): Response
    {
        return $this->render('creations/index.html.twig', [
            'controller_name'   => 'CreationsController',
            'title'             => 'Créations'
        ]);
    }

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
