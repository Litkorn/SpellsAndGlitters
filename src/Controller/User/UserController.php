<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\EditPassType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil', name:'app_profil_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('User/dashboard.html.twig');
    }

    #[Route('/informations/{id}', name: 'informations')]
    public function manageInfos(Request $request, UserRepository $userRepo, User $user = null)
    {
        if($user == null || $userRepo->isSameUser($request, $user) == false){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('User/info.html.twig', [
            'user'  => $user
        ]);
    }

    #[Route('/editPass/{id}', name: 'editPass')]
    public function editPass(Request $request, UserRepository $userRepo, User $user = null)
    {
        if($user == null || $userRepo->isSameUser($request, $user) == false){
            return $this->redirectToRoute('app_home');
        }

        $route = $request->headers->get('referer');

        $form = $this->createForm(EditPassType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($route);
        }

        return $this->render('User/editPass.html.twig', [
            'form'   => $form
        ]);

    }

    #[Route('/favorites', name:'favorites')]
    public function manageFavorites(Request $request, User $user = null)
    {
        return $this->render('User/favorites.html.twig');
    }
}
