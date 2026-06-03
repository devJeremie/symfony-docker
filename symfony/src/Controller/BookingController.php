<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Travel;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/booking', name: 'booking_')]
class BookingController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookingRepository $bookingRepository,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $bookings = $this->bookingRepository->findConfirmed();

        return $this->render('booking/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/travel/{id}', name: 'new', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(Request $request, Travel $travel): Response
    {
        $booking = new Booking();
        $booking->setTravel($travel);

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $booking->setFirstName($data['firstName'] ?? '');
            $booking->setLastName($data['lastName'] ?? '');
            $booking->setEmail($data['email'] ?? '');
            $booking->setPhone($data['phone'] ?? null);
            $booking->setNumberOfPersons((int) ($data['numberOfPersons'] ?? 1));
            $booking->setStatus(Booking::STATUS_PENDING);
            $booking->calculateTotalPrice();

            $availableSeats = $travel->getAvailableSeats();
            if ($booking->getNumberOfPersons() > $availableSeats) {
                $this->addFlash('error', "Seulement $availableSeats place(s) disponible(s) pour ce voyage.");
                return $this->render('booking/new.html.twig', [
                    'booking' => $booking,
                    'travel' => $travel,
                ]);
            }

            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès !');
            return $this->redirectToRoute('booking_show', ['id' => $booking->getId()]);
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'travel' => $travel,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/{id}/confirm', name: 'confirm', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function confirm(Booking $booking): Response
    {
        $booking->setStatus(Booking::STATUS_CONFIRMED);
        $this->entityManager->flush();

        $this->addFlash('success', 'La réservation a été confirmée.');
        return $this->redirectToRoute('booking_show', ['id' => $booking->getId()]);
    }

    #[Route('/{id}/cancel', name: 'cancel', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function cancel(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('cancel' . $booking->getId(), $request->request->get('_token'))) {
            $booking->setStatus(Booking::STATUS_CANCELLED);
            $this->entityManager->flush();
            $this->addFlash('info', 'La réservation a été annulée.');
        }

        return $this->redirectToRoute('booking_index');
    }
}
