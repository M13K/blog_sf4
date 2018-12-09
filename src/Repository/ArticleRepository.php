<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Category;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

//    public function getArticleByCategorie(Category $category)
//    {
//        $qb = $this->createQueryBuilder('a')
//            ->leftJoin('a.category', 'c')
//            ->addSelect('c')
//            ->where('c = :category')
//            ->setParameter('category',  $category)
//        ;
//
//        return $qb->getQuery()
//            ->getResult();
//    }

//    public function getArticleByCategorie(int $categeoryId)
//    {
//        $qb = $this->createQueryBuilder('a')
//            ->leftJoin('a.category', 'c')
//            ->where('c.id = :categoryId')
//            ->setParameter('categoryId',  $categeoryId)
//        ;
//
//        return $qb->getQuery()
//            ->getResult();
//    }

//    public function findByCategorie(string $title)
//    {
//        $qb = $this->createQueryBuilder('a')
//            ->leftJoin('a.category', 'category')
//            ->where('category.title = :title')
//            ->setParameter('title', $title)
//        ;
//
//        return $qb->getQuery()
//            ->getResult();
//    }

    public function findBYCategory(string $title)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a, c')
            ->leftJoin('a.category', 'c')
            ->where('c.title = :title')
            ->setParameter('title',  $title)
        ;

        return $qb->getQuery()
            ->getResult();
    }


}

