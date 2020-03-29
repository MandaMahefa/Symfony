<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @param PropertySearch $propertySearch
     * @return Query
     */
    public function findAllvisibleQuery(PropertySearch $propertySearch) : Query {
        $query  = $this->findVisibleQuery();
        if ($propertySearch->getMinSurface()){
            $query->andWhere('p.surface <= :minSurface')
                ->setParameter('minSurface', $propertySearch->getMinSurface());
        }
        if ($propertySearch->getMaxPrice()){
            $query->andWhere('p.price >= :maxPrice')
                ->setParameter('maxPrice', $propertySearch->getMaxPrice());
        }
        return $query->getQuery();
    }
    public function findLatest(){
        return $this->findVisibleQuery()->setMaxResults(4)->getQuery()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function findVisibleQuery() : QueryBuilder{
        return $this->createQueryBuilder('p')
            ->where('p.solde = false');
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
