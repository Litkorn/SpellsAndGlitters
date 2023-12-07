<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\EditPassType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profil', name:'app_profil_')]
class UserController extends AbstractController
{
    /* profil dashboard */
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('User/dashboard.html.twig');
    }

    /* profil informations page */
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

    /* profil edit password page */
    #[Route('/editPass/{id}', name: 'editPass')]
    public function editPass(EntityManagerInterface $em, UserPasswordHasherInterface $hasher, Request $request, UserRepository $userRepo, User $user = null)
    {
        /* check if id is the same as identified user's one */
        if($user == null || $userRepo->isSameUser($request, $user) == false){
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(EditPassType::class, $user);
        $form->handleRequest($request);

        /* if my form is submitted */
        if ($form->isSubmitted()) {
            /* if the old password is not valid, an error is added, if good and the form is valid, change the password*/
            if($hasher->isPasswordValid($user, $form->get('oldPassword')->getData()) == false){
                $form->get('oldPassword')->addError(new FormError('Le mot de passe indiqué est incorrect'));
            } else if ($form->isValid()){
                $user->setPassword($hasher->hashPassword($user, $form->get('plainPassword')->getData()));
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre mot de passe a bien été modifié !');
                return $this->redirectToRoute('app_profil_informations', [
                    'id'    => $user->getId()
                ]);
            }
        }

        return $this->render('User/editPass.html.twig', [
            'form'  => $form,
            'user'  => $user
        ]);

    }


    /* profile favorites */
    #[Route('/favorites', name:'favorites')]
    public function manageFavorites(Request $request, User $user = null)
    {
        return $this->render('User/favorites.html.twig');
    }
}
