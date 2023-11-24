<?php

namespace App\Controller\Main;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ItemRepository $itemRepo): Response
    {
        $creations = $itemRepo->findBy(['isActive' => true], ['createdAt' => 'DESC'], 4);


        return $this->render('Main/home/index.html.twig', [
            'creations'         => $creations
        ]);
    }
}
