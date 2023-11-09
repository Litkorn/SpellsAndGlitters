<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('Main/home/index.html.twig', [
            'controller_name'   => 'HomeController',
            'title'             => 'Accueil | Spells and Glitters'
        ]);
    }
}
