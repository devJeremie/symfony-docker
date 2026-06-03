<?php

namespace App\Repository;

use App\Entity\Travel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Travel>
 */
class TravelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Travel::class);
    }

    /**
     * Récupère les voyages publiés triés par date de départ.
     */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.status = :status')
            ->setParameter('status', 'published')
            ->andWhere('t.departureDate > :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('t.departureDate', 'ASC')
            ->leftJoin('t.destination', 'd')
            ->addSelect('d')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les voyages d'une destination spécifique.
     */
    public function findByDestination(int $destinationId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.destination = :destinationId')
            ->setParameter('destinationId', $destinationId)
            ->andWhere('t.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('t.departureDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les voyages avec leurs réservations (eager loading).
     */
    public function findWithBookings(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.bookings', 'b')
            ->addSelect('b')
            ->leftJoin('t.destination', 'd')
            ->addSelect('d')
            ->orderBy('t.departureDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche de voyages par budget maximum.
     */
    public function findByMaxPrice(float $maxPrice): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->andWhere('t.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('t.price', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
