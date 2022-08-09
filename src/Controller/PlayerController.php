<?php

namespace App\Controller;

use DateTime;
use App\Form\FilterType;
use App\Repository\JobRepository;
use App\Entity\PlayerAdvertisement;
use App\Form\PlayerAdvertisementType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlayerAdvertisementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('', name: 'app_player_index')]
    public function index(PlayerAdvertisementRepository $playerAdvertisementRepository, PaginatorInterface $paginator, Request $request, JobRepository $jobRepository): Response
    {
        $user = $this->getUser();
        $ownAnAdvertise = false;
        // si l'utilisateur est connecté et qu'il possède une annonce il aura alors accès au bouton de modification et suppression grâce à la variable qui sera envoyé à Twig
        if($user && $user->getPlayerAdvertisement()){
            $ownAnAdvertise = true;
        }

        // recuperation de tout les jobs
        $jobs = $jobRepository->findAll();

        // recuperation de toute les annonces, ordonné de l'annonce crée la plus récente à la plus ancienne
        $playerAdvertisement = $playerAdvertisementRepository->findBy([], ['createdAt' => 'DESC']);
        $numberOfAdvertisement = $playerAdvertisementRepository->count([]);

        // création du formulaire à partir de FilterType qui servira de filtres
        $form = $this->createForm(FilterType::class);
        $form->remove('dataCenter');
        $form->handleRequest($request);

        // losrque le formulaire est valide et est submit
        if($form->isSubmitted() && $form->isValid()){
            // utilisation de la queryBuilder du repository en y passant le formulaire et la liste des jobs en parametre
            $playerAdvertisement = $playerAdvertisementRepository->filterPlayerAdvertisement($request);

            // utilisation de paginator pour avoir une pagination, 6 élément seront visible par page
            $playerAdvertisement = $paginator->paginate($playerAdvertisement, $request->query->getInt('page', 1), 6);

            // render de la page index, envoi de la liste des annonces, des jobs, et du formulaire
            return $this->render('player/index.html.twig', [
                'playerAdvertisement' => $playerAdvertisement,
                'jobs' => $jobs,
                'form'=> $form->createView(),
                'numberOfAdvertisement' => $numberOfAdvertisement,
                'ownAnAdvertise' => $ownAnAdvertise,
                'user' => $user
            ]);
        }

        // par défaut lorsque qu'aucun filtre n'est actif

        // utilisation de paginator pour avoir une pagination, 6 élément seront visible par page
        $playerAdvertisement = $paginator->paginate($playerAdvertisement, $request->query->getInt('page', 1), 6);
        
        // render de la page index, envoi de la liste des annonces, des jobs, et du formulaire
        return $this->render('player/index.html.twig', [
            'playerAdvertisement' => $playerAdvertisement,
            'jobs' => $jobs,
            'form'=> $form->createView(),
            'numberOfAdvertisement' => $numberOfAdvertisement,
            'ownAnAdvertise' => $ownAnAdvertise,
            'user' => $user
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_player_detail')]
    public function advertiseDetail(PlayerAdvertisement $playerAdvertisement): Response
    {
        $user = $this->getUser();
        $isTheOwner = false;
        // si l'utilisateur est connecté et qu'il est l'auteur de l'annonce il aura alors accès au bouton de modification et suppression grâce à la variable qui sera envoyé à Twig
        if($user && $user->getPlayerAdvertisement() && $user->getPlayerAdvertisement()->getId() == $playerAdvertisement->getId()){
            $isTheOwner = true;
        }
        // vérification des role présent dans l'annonce pour n'afficher que les rôles effectivement présent
        $jobArray = $playerAdvertisement->getJob();
        $tank = false;
        $healer = false;
        $dps = false;
        foreach($jobArray as $job){
            if($job->getRole() == 'Tank'){
                $tank = true;
            } else if($job->getRole() == 'Dps'){
                $dps = true;
            } else if($job->getRole() == 'Healer'){
                $healer = true;
            }
        }

        return $this->render('player/advertiseDetail.html.twig', [
            'playerAdvertisement' => $playerAdvertisement,
            'containTank' => $tank,
            'containHealer' => $healer,
            'containDps' => $dps,
            'isTheOwner' => $isTheOwner
        ]);
    }

    #[Route('/create', name: 'app_player_create')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
    public function create(Request $request, EntityManagerInterface $em, JobRepository $jobRepository): Response
    {
        $jobs = $jobRepository->findAll();
        $playerAdvertisement = new PlayerAdvertisement();
        $form = $this->createForm(PlayerAdvertisementType::class, $playerAdvertisement);
        $form->handleRequest($request);
        
        $user = $this->getUser();
        $ownAnAdvertise = false;
        // si l'utilisateur est connecté et qu'il possède une annonce alors le formulaire ne sera pas affiché
        if($user && $user->getPlayerAdvertisement()){
            $ownAnAdvertise = true;
        }

        if($form->isSubmitted() && $form->isValid()){
            if(empty($request->get('player_advertisement')['job'])){
                $errorJob = 'Vous devez sélectionner au moins un Job';
                $playerAdvertisement->setBannerFile(null);
                return $this->render('player/create.html.twig', [
                    'form'=>$form->createView(),
                    'jobs' => $jobs,
                    'ownAnAdvertise' => $ownAnAdvertise,
                    'errorJob' => $errorJob
                ]);
            }
            $playerAdvertisement
                    ->setCreatedAt(new DateTime('now'))
                    ->setOwner($this->getUser());
            $jobList = $request->get('player_advertisement')['job'];

            foreach($jobList as $jobId){
                // récupération de l'objet Job lié à l'id
                $job = $jobRepository->findOneBy(['id'=> $jobId]);
                $playerAdvertisement->addJob($job);
            }
            
            $em->persist($playerAdvertisement);
            $em->flush();

            return $this->redirectToRoute('app_player_detail', ['id' => $playerAdvertisement->getId()]);
        }

        return $this->render('player/create.html.twig', [
            'form'=>$form->createView(),
            'jobs' => $jobs,
            'ownAnAdvertise' => $ownAnAdvertise
        ]);
    }

    
    #[Route('/edit/{id<\d+>}', name: 'app_player_edit')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
    public function edit(PlayerAdvertisement $playerAdvertisement, Request $request, EntityManagerInterface $em, JobRepository $jobRepository): Response
    {
        $user = $this->getUser();
        // si l'utilisateur actuel n'est pas l'auteur de l'annonce il sera redirigé vers la page d'index
        if($user->getId() != $playerAdvertisement->getOwner()->getId()){
            return $this->redirectToRoute('app_player_index');
        };

        $jobs = $jobRepository->findAll();
        $form = $this->createForm(PlayerAdvertisementType::class, $playerAdvertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // si aucun job n'est envoyé on affichera une erreur
            if(empty($request->get('player_advertisement')['job'])){
                $errorJob = 'Vous devez sélectionner au moins un Job';
                $playerAdvertisement->setBannerFile(null);
                return $this->render('player/edit.html.twig', [
                    'form'=>$form->createView(),
                    'jobs' => $jobs,
                    'playerAdvertisement' => $playerAdvertisement,
                    'errorJob' => $errorJob,
                ]);
            }

            $jobList = $request->get('player_advertisement')['job'];
            // boucle sur les job actuel pour tous les retirer
            foreach($playerAdvertisement->getJob() as $job){
                $playerAdvertisement->removeJob($job);
            }
            // boucle sur le tableau de job envoyé par le fomulaire pour les ajouter
            foreach($jobList as $jobId){
                // récupération de l'objet Job lié à l'id
                $job = $jobRepository->findOneBy(['id'=> $jobId]);
                $playerAdvertisement->addJob($job);
            }
            
            $em->persist($playerAdvertisement);
            $em->flush();
            // pour eviter les erreurs de sérialization avec un changement d'image
            $playerAdvertisement->setBannerFile(null);

            return $this->redirectToRoute('app_player_detail',[ 'id' => $playerAdvertisement->getId()]);
        }

        $playerAdvertisement->setBannerFile(null);
        
        return $this->render('player/edit.html.twig', [
            'form'=>$form->createView(),
            'jobs' => $jobs,
            'playerAdvertisement' => $playerAdvertisement
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'app_player_delete')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
    public function deleteAdvertise(Request $request, EntityManagerInterface $em, PlayerAdvertisement $playerAdvertisement): Response
    {
        $user = $this->getUser();
        $submittedToken = $request->request->get('token');

        if($this->isCsrfTokenValid('delete_advertise', $submittedToken) && $user == $playerAdvertisement->getOwner()){
            $em->remove($playerAdvertisement);
            $em->flush();

            return $this->redirectToRoute('app_player_index');
        }
        return $this->redirectToRoute('app_player_detail', ['id' => $playerAdvertisement->getId()]);
    }
}