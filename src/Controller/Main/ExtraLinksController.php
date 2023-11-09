<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtraLinksController extends AbstractController
{
    #[Route('/CGU', name: 'app_cgu')]
    public function index(): Response
    {
        return $this->render('Main/extra_links/index.html.twig', [
            'title' => 'CGU | Spells and Glitters',
            'controller_name' => 'CGU'
        ]);
    }
}
