<?php

namespace App\Controller\Main;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ItemRepository $itemRepo): Response
    {
        $creations = $itemRepo->findBy([], ['createdAt' => 'DESC'], 4);


        return $this->render('Main/home/index.html.twig', [
            'controller_name'   => 'HomeController',
            'title'             => 'Accueil | Spells and Glitters',
            'creations'         => $creations
        ]);
    }
}
