<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        TravelRepository $travelRepository,
        DestinationRepository $destinationRepository
    ): Response {
        $travels = $travelRepository->findPublished();
        $destinations = $destinationRepository->findWithActiveTravel();

        return $this->render('home/index.html.twig', [
            'travels' => $travels,
            'destinations' => $destinations,
        ]);
    }
}
