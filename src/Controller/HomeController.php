<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlayerAdvertisementRepository;
use App\Repository\RecruitAdvertisementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecruitAdvertisementRepository $recruitAdvertisementRepository, PlayerAdvertisementRepository $playerAdvertisementRepository): Response
    {     
        $numberOfPlayerAdvertisement = $playerAdvertisementRepository->count([]);
        $numberOfRecruitAdvertisement = $recruitAdvertisementRepository->count([]);
        return $this->render('home/index.html.twig', [
            'numberOfPlayerAdvertisement' => $numberOfPlayerAdvertisement,
            'numberOfRecruitAdvertisement' => $numberOfRecruitAdvertisement,
        ]);
    }
}
