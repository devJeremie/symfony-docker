<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Repository\TravelRepository;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/travel', name: 'travel_')]
class TravelController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TravelRepository $travelRepository,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $travels = $this->travelRepository->findPublished();

        return $this->render('travel/index.html.twig', [
            'travels' => $travels,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Travel $travel): Response
    {
        return $this->render('travel/show.html.twig', [
            'travel' => $travel,
        ]);
    }

    private function handleImageUpload(Request $request, Travel $travel): void
    {
        $imageFile = $request->files->get('image');
        if (!$imageFile || !$imageFile->isValid()) {
            return;
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/images/travels/';

        if ($travel->getImageName()) {
            $oldFile = $uploadDir . $travel->getImageName();
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $newFilename = uniqid('travel-') . '.' . $imageFile->guessExtension();
        $imageFile->move($uploadDir, $newFilename);
        $travel->setImageName($newFilename);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, DestinationRepository $destinationRepository): Response
    {
        $travel = new Travel();
        $destinations = $destinationRepository->findAll();

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $travel->setTitle($data['title'] ?? '');
            $travel->setDescription($data['description'] ?? null);
            $travel->setPrice($data['price'] ?? '0');
            $travel->setMaxParticipants((int) ($data['maxParticipants'] ?? 1));
            $travel->setStatus($data['status'] ?? 'draft');

            if (!empty($data['departureDate'])) {
                $travel->setDepartureDate(new \DateTime($data['departureDate']));
            }
            if (!empty($data['returnDate'])) {
                $travel->setReturnDate(new \DateTime($data['returnDate']));
            }

            $destination = $destinationRepository->find((int) ($data['destination'] ?? 0));
            if ($destination) {
                $travel->setDestination($destination);
            }

            $this->handleImageUpload($request, $travel);

            $this->entityManager->persist($travel);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le voyage a été créé avec succès !');
            return $this->redirectToRoute('travel_show', ['id' => $travel->getId()]);
        }

        return $this->render('travel/new.html.twig', [
            'travel' => $travel,
            'destinations' => $destinations,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Travel $travel, DestinationRepository $destinationRepository): Response
    {
        $destinations = $destinationRepository->findAll();

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $travel->setTitle($data['title'] ?? $travel->getTitle());
            $travel->setDescription($data['description'] ?? null);
            $travel->setPrice($data['price'] ?? $travel->getPrice());
            $travel->setMaxParticipants((int) ($data['maxParticipants'] ?? $travel->getMaxParticipants()));
            $travel->setStatus($data['status'] ?? $travel->getStatus());

            if (!empty($data['departureDate'])) {
                $travel->setDepartureDate(new \DateTime($data['departureDate']));
            }
            if (!empty($data['returnDate'])) {
                $travel->setReturnDate(new \DateTime($data['returnDate']));
            }

            $destination = $destinationRepository->find((int) ($data['destination'] ?? 0));
            if ($destination) {
                $travel->setDestination($destination);
            }

            $this->handleImageUpload($request, $travel);

            $this->entityManager->flush();

            $this->addFlash('success', 'Le voyage a été modifié avec succès !');
            return $this->redirectToRoute('travel_show', ['id' => $travel->getId()]);
        }

        return $this->render('travel/edit.html.twig', [
            'travel' => $travel,
            'destinations' => $destinations,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Travel $travel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $travel->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($travel);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le voyage a été supprimé.');
        }

        return $this->redirectToRoute('travel_index');
    }
}
