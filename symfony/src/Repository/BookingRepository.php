<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * Récupère les réservations d'un voyage spécifique.
     */
    public function findByTravel(int $travelId): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.travel = :travelId')
            ->setParameter('travelId', $travelId)
            ->orderBy('b.bookedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les réservations confirmées.
     */
    public function findConfirmed(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.status = :status')
            ->setParameter('status', 'confirmed')
            ->leftJoin('b.travel', 't')
            ->addSelect('t')
            ->orderBy('b.bookedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par email du client.
     */
    public function findByEmail(string $email): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.email = :email')
            ->setParameter('email', $email)
            ->orderBy('b.bookedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre total de personnes réservées pour un voyage.
     */
    public function countPersonsForTravel(int $travelId): int
    {
        $result = $this->createQueryBuilder('b')
            ->select('SUM(b.numberOfPersons)')
            ->andWhere('b.travel = :travelId')
            ->setParameter('travelId', $travelId)
            ->andWhere('b.status != :status')
            ->setParameter('status', 'cancelled')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }
}
