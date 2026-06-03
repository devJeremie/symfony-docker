<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/destination', name: 'destination_')]
class DestinationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DestinationRepository $destinationRepository,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $search = $request->query->get('search', '');

        if ($search) {
            $destinations = $this->destinationRepository->search($search);
        } else {
            $destinations = $this->destinationRepository->findAll();
        }

        return $this->render('destination/index.html.twig', [
            'destinations' => $destinations,
            'search' => $search,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Destination $destination): Response
    {
        return $this->render('destination/show.html.twig', [
            'destination' => $destination,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $destination = new Destination();

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $destination->setName($data['name'] ?? '');
            $destination->setCountry($data['country'] ?? '');
            $destination->setDescription($data['description'] ?? null);
            $destination->setIataCode($data['iataCode'] ?? null);

            $this->entityManager->persist($destination);
            $this->entityManager->flush();

            $this->addFlash('success', 'La destination a été créée avec succès !');
            return $this->redirectToRoute('destination_show', ['id' => $destination->getId()]);
        }

        return $this->render('destination/new.html.twig', [
            'destination' => $destination,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Destination $destination): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $destination->setName($data['name'] ?? $destination->getName());
            $destination->setCountry($data['country'] ?? $destination->getCountry());
            $destination->setDescription($data['description'] ?? null);
            $destination->setIataCode($data['iataCode'] ?? null);

            $this->entityManager->flush();

            $this->addFlash('success', 'La destination a été modifiée avec succès !');
            return $this->redirectToRoute('destination_show', ['id' => $destination->getId()]);
        }

        return $this->render('destination/edit.html.twig', [
            'destination' => $destination,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Destination $destination): Response
    {
        if ($this->isCsrfTokenValid('delete' . $destination->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($destination);
            $this->entityManager->flush();
            $this->addFlash('success', 'La destination a été supprimée.');
        }

        return $this->redirectToRoute('destination_index');
    }
}
