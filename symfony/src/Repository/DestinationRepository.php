<?php

namespace App\Repository;

use App\Entity\Destination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Destination>
 */
class DestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Destination::class);
    }

    /**
     * Recherche les destinations par pays.
     */
    public function findByCountry(string $country): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.country = :country')
            ->setParameter('country', $country)
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les destinations ayant au moins un voyage actif.
     */
    public function findWithActiveTravel(): array
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.travels', 't')
            ->andWhere('t.status = :status')
            ->setParameter('status', 'published')
            ->groupBy('d.id')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par nom ou pays (recherche partielle).
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.name LIKE :query OR d.country LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
