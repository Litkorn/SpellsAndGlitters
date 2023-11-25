<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /* function to return creations paginated */
    /* page = number of the actual page, catSlug = slug of the category, limit = number max of products to find */
    public function findCreationsPaginated(int $page, string $catSlug, int $limit = 6, string $orderType, string $order): array
    {
        if($orderType == "alpha"){
            $ordType = "creations.title";
        } else {
            $ordType = "creations.createdAt";
        }
        if($order == "asc"){
            $ord = "ASC";
        } else {
            $ord = "DESC";
        }

        /* absolute value of limit to avoid error */
        $limit = abs($limit);

        $result = [];

        /* calcul of the first resultat that should be displayed */
        $firstResult = $page * $limit - $limit;

        /* creation of the query, finding creations by the category's slug, if slug is empty then it takes all creations */
        if($catSlug){
            $query = $this->getEntityManager()->createQueryBuilder()
                            ->select('c', 'creations')
                            ->from('App\Entity\Item', 'creations')
                            ->join('creations.category', 'c')
                            ->where("c.slug = '$catSlug'")
                            ->andWhere('creations.isActive = true')
                            ->setMaxResults($limit)
                            ->setFirstResult($firstResult)
                            ->orderBy($ordType, $ord);
        } else {
            $query = $this->getEntityManager()->createQueryBuilder()
                            ->select('creations')
                            ->from('App\Entity\Item', 'creations')
                            ->where('creations.isActive = true')
                            ->setMaxResults($limit)
                            ->setFirstResult($firstResult)
                            ->orderBy($ordType, $ord );
        }

        /* making the pagination */
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        if (empty($data))
            return ($result);

        /* calculation the number of pages */
        $pages = ceil($paginator->count() / $limit);

        /* puting datas in result */
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;
        $result['order'] = $orderType . '_' . $order;

        return ($result);
    }

//    /**
//     * @return Item[] Returns an array of Item objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
