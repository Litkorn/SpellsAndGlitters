<?php

namespace App\Controller\Creations;

use App\Entity\Item;
use App\Entity\Category;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /* Creation list (by Category) */
    #[Route('/{slug}', name: 'category')]
    public function indexCategory(Category $category, ItemRepository $itemRepo, Request $request)
    {
        $slug = $category->getSlug();

        /* finding page number in the url */
        $page = $request->query->getInt('page', 1);

        // $creations = $category->getItems();
        $creations = $itemRepo->findCreationsPaginated($page, $slug, 4);

        return $this->render('Creations/index.html.twig', [
            'creations'     => $creations,
            'slug'          => $slug
        ]);
    }

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
