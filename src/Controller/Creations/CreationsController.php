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

        $creations = $itemRepo->findCreationsPaginated($page, "", $orderType, $order, 9);
        /* send slug empty so the vue knows wich path to set */
        return $this->render('Creations/index.html.twig', [
            'creations'         => $creations,
            'slug'              => "",
            'title'             => 'Toutes mes créations'
        ]);
    }

    /* Creation list (by Category) */
    #[Route('/category/{slug}', name: 'category')]
    public function indexCategory(Category $category, ItemRepository $itemRepo, Request $request)
    {
        $slug = $category->getSlug();
        $title = $category->getTitle();

        /* finding number of the page in the url */
        $page = $request->query->getInt('page', 1);
        /* finding order and orderType to sort the creations */
        $order = $request->query->getString('order', 'desc');
        $orderType = $request->query->getString('orderType', 'date');

        $creations = $itemRepo->findCreationsPaginated($page, $slug, $orderType, $order, 9, false);

        return $this->render('Creations/index.html.twig', [
            'creations'     => $creations,
            'slug'          => $slug,
            'title'         => $title
        ]);
    }

    /* New creations list */
    #[Route('/new', name: 'new')]
    public function indexNew(ItemRepository $itemRepo, Request $request)
    {
        /* finding number of the page in the url */
        $page = $request->query->getInt('page', 1);
        /* finding order and orderType to sort the creations */
        $order = $request->query->getString('order', 'desc');
        $orderType = $request->query->getString('orderType', 'date');

        $creations = $itemRepo->findCreationsPaginated($page, "", $orderType, $order, 9, true);

        return $this->render('Creations/index.html.twig', [
            'creations'         => $creations,
            'slug'              => "",
            'title'             => 'Mes dernières créations',
            'new'               => true
        ]);
    }

    /* Creations Details */
    #[Route('/details/{slug}', name: 'details')]
    public function showCreation(ItemRepository $itemRepo, Item $creation = null)
    {
        if($creation == null){
            return $this->redirectToRoute('app_home');
        } else {
            $id = $creation->getId();
            $catId = $creation->getCategory()->getId();
            $randCreations = $itemRepo->findRandomCreations($id, $catId, 4);
            return $this->render('Creations/show.html.twig', [
                'creation'      => $creation,
                'randCreations' => $randCreations
            ]);
        }
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
