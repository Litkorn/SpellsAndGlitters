<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name:'app_profil_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('User/dashboard.html.twig');
    }

    #[Route('/informations', name: 'informations')]
    public function manageInfos()
    {
        return $this->render('User/info.html.twig');
    }

    #[Route('/favorites', name:'favorites')]
    public function manageFavorites()
    {
        return $this->render('User/favorites.html.twig');
    }
}
