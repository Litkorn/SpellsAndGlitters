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
    public function index(ItemRepository $itemRepo, Request $request): Response
    {
        /* finding number of the page in the url */
        $page = $request->query->getInt('page', 1);
        /* finding order and orderType to sort the creations */
        $order = $request->query->getString('order', 'desc');
        $orderType = $request->query->getString('orderType', 'date');

        $creations = $itemRepo->findCreationsPaginated($page, "", 9, $orderType, $order);
        /* send slug empty so the vue knows wich path to set */
        return $this->render('Creations/index.html.twig', [
            'creations'         => $creations,
            'slug'              => "",
            'title'             => 'Toutes mes créations'
        ]);
    }

    /* Creation list (by Category) */
    #[Route('/{slug}', name: 'category')]
    public function indexCategory(Category $category, ItemRepository $itemRepo, Request $request)
    {
        $slug = $category->getSlug();
        $title = $category->getTitle();

        /* finding number of the page in the url */
        $page = $request->query->getInt('page', 1);
        /* finding order and orderType to sort the creations */
        $order = $request->query->getString('order', 'desc');
        $orderType = $request->query->getString('orderType', 'date');

        $creations = $itemRepo->findCreationsPaginated($page, $slug, 9, $orderType, $order);

        return $this->render('Creations/index.html.twig', [
            'creations'     => $creations,
            'slug'          => $slug,
            'title'         => $title
        ]);
    }

    /* Routes to handle favorites */
    #[IsGranted('ROLE_USER')]
    #[Route('/favoris/ajout/{id}', name: 'add_favorite')]
    public function addFavoris(Item $item, EntityManagerInterface $em, Request $request)
    {
        if(!$item){
            throw new NotFoundHttpException('Pas de création trouvée');
        }
        $route = $request->headers->get('referer');
        $item->addFavorite($this->getUser());
        $em->persist($item);
        $em->flush();
        return $this->redirect($route);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/favoris/suppression/{id}', name: 'remove_favorite')]
    public function removeFavoris(Item $item, EntityManagerInterface $em, Request $request)
    {
        if(!$item){
            throw new NotFoundHttpException('Pas de création trouvée');
        }
        $route = $request->headers->get('referer');
        $item->removeFavorite($this->getUser());
        $em->persist($item);
        $em->flush();
        return $this->redirect($route);
    }
}
