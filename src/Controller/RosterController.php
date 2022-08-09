<?php

namespace App\Controller;

use DateTime;
use App\Form\FilterType;
use App\Repository\JobRepository;
use App\Entity\RecruitAdvertisement;
use App\Form\RecruitAdvertisementType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecruitAdvertisementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/roster')]
class RosterController extends AbstractController
{
    #[Route('', name: 'app_roster_index')]
    public function index(RecruitAdvertisementRepository $recruitAdvertisementRepository, PaginatorInterface $paginator, Request $request, JobRepository $jobRepository): Response
    {
        $user = $this->getUser();
        $ownAnAdvertise = false;
        // si l'utilisateur est connecté et qu'il possède une annonce il aura alors accès au bouton de modification et suppression grâce à la variable qui sera envoyé à Twig
        if($user && $user->getRecruitAdvertisement()){
            $ownAnAdvertise = true;
        }
        // recuperation de tout les jobs
        $jobs = $jobRepository->findAll();

        // recuperation de toute les annonces, ordonné de l'annonce crée la plus récente à la plus ancienne
        $recruitAdvertisement = $recruitAdvertisementRepository->findBy([], ['createdAt' => 'DESC']);
        $numberOfAdvertisement = $recruitAdvertisementRepository->count([]);
        // création du formulaire à partir de FilterType qui servira de filtres
        $form = $this->createForm(FilterType::class);
        $form->remove('homeWorld');
        $form->handleRequest($request);

        // losrque le formulaire est valide et est submit
        if($form->isSubmitted() && $form->isValid()){
            // utilisation de la queryBuilder du repository en y passant le formulaire et la liste des jobs en parametre
            $recruitAdvertisement = $recruitAdvertisementRepository->filterRecruitAdvertisement($request);

            // utilisation de paginator pour avoir une pagination, 6 élément seront visible par page
            $recruitAdvertisement = $paginator->paginate($recruitAdvertisement, $request->query->getInt('page', 1), 6);

            // render de la page index, envoi de la liste des annonces, des jobs, et du formulaire
            return $this->render('roster/index.html.twig', [
                'recruitAdvertisement' => $recruitAdvertisement,
                'jobs' => $jobs,
                'form'=> $form->createView(),
                'numberOfAdvertisement' => $numberOfAdvertisement,
                'ownAnAdvertise' => $ownAnAdvertise,
                'user' => $user
            ]);
        }

        // par défaut lorsque qu'aucun filtre n'est actif

        // utilisation de paginator pour avoir une pagination, 6 élément seront visible par page
        $recruitAdvertisement = $paginator->paginate($recruitAdvertisement, $request->query->getInt('page', 1), 6);
        
        // render de la page index, envoi de la liste des annonces, des jobs, et du formulaire
        return $this->render('roster/index.html.twig', [
            'recruitAdvertisement' => $recruitAdvertisement,
            'jobs' => $jobs,
            'form'=> $form->createView(),
            'numberOfAdvertisement' => $numberOfAdvertisement,
            'ownAnAdvertise' => $ownAnAdvertise,
            'user' => $user
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_roster_detail')]
    public function advertiseDetail(RecruitAdvertisement $recruitAdvertisement): Response
    {
        $user = $this->getUser();
        $isTheOwner = false;
        // si l'utilisateur est connecté et qu'il est l'auteur de l'annonce il aura alors accès au bouton de modification et suppression grâce à la variable qui sera envoyé à Twig
        if($user && $user->getRecruitAdvertisement() && $user->getRecruitAdvertisement()->getId() == $recruitAdvertisement->getId()){
            $isTheOwner = true;
        }
        // vérification des role présent dans l'annonce pour n'afficher que les rôles effectivement présent
        $jobArray = $recruitAdvertisement->getJob();
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

        return $this->render('roster/advertiseDetail.html.twig', [
            'recruitAdvertisement' => $recruitAdvertisement,
            'containTank' => $tank,
            'containHealer' => $healer,
            'containDps' => $dps,
            'isTheOwner' => $isTheOwner,
        ]);
    }

    #[Route('/create', name: 'app_roster_create')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
    public function create(Request $request, EntityManagerInterface $em, JobRepository $jobRepository): Response
    {
        $jobs = $jobRepository->findAll();
        $recruitAdvertisement = new RecruitAdvertisement();
        $form = $this->createForm(RecruitAdvertisementType::class, $recruitAdvertisement);
        $form->handleRequest($request);
        
        $user = $this->getUser();
        $ownAnAdvertise = false;
        // si l'utilisateur est connecté et qu'il possède une annonce alors le formulaire ne sera pas affiché
        if($user && $user->getRecruitAdvertisement()){
            $ownAnAdvertise = true;
        }
        if($form->isSubmitted() && $form->isValid()){
            if(empty($request->get('recruit_advertisement')['job'])){
                $errorJob = 'Vous devez sélectionner au moins un Job';
                $recruitAdvertisement->setBannerFile(null);
                return $this->render('roster/create.html.twig', [
                    'form'=>$form->createView(),
                    'jobs' => $jobs,
                    'ownAnAdvertise' => $ownAnAdvertise,
                    'errorJob' => $errorJob
                ]);
            }

            $recruitAdvertisement
                    ->setCreatedAt(new DateTime('now'))
                    ->setOwner($this->getUser());
            $jobList = $request->get('recruit_advertisement')['job'];
            foreach($jobList as $jobId){
                // récupération de l'objet Job lié à l'id
                $job = $jobRepository->findOneBy(['id'=> $jobId]);
                $recruitAdvertisement->addJob($job);
            }
            
            $em->persist($recruitAdvertisement);
            $em->flush();

            return $this->redirectToRoute('app_roster_detail', ['id' => $recruitAdvertisement->getId()]);
        }

        return $this->render('roster/create.html.twig', [
            'form'=>$form->createView(),
            'jobs' => $jobs,
            'ownAnAdvertise' => $ownAnAdvertise
        ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'app_roster_edit')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
    public function edit(RecruitAdvertisement $recruitAdvertisement, Request $request, EntityManagerInterface $em, JobRepository $jobRepository): Response
    {
        $user = $this->getUser();

        // si l'utilisateur actuel n'est pas l'auteur de l'annonce il sera redirigé vers la page d'index
        if($user->getId() != $recruitAdvertisement->getOwner()->getId()){
            return $this->redirectToRoute('app_roster_index');
        };

        $jobs = $jobRepository->findAll();
        $form = $this->createForm(RecruitAdvertisementType::class, $recruitAdvertisement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(empty($request->get('recruit_advertisement')['job'])){
                $errorJob = 'Vous devez sélectionner au moins un Job';
                $recruitAdvertisement->setBannerFile(null);
                return $this->render('roster/edit.html.twig', [
                    'form'=>$form->createView(),
                    'jobs' => $jobs,
                    'recruitAdvertisement' => $recruitAdvertisement,
                    'errorJob' => $errorJob
                ]);
            }

            $jobList = $request->get('recruit_advertisement')['job'];
            // boucle sur les job actuel pour tous les retirer
            foreach($recruitAdvertisement->getJob() as $job){
                $recruitAdvertisement->removeJob($job);
            }
            // boucle sur le tableau de job envoyé par le fomulaire pour les ajouter
            foreach($jobList as $jobId){
                // récupération de l'objet Job lié à l'id
                $job = $jobRepository->findOneBy(['id'=> $jobId]);
                $recruitAdvertisement->addJob($job);
            }
            
            $em->persist($recruitAdvertisement);
            $em->flush();
            // pour eviter les erreurs de sérialization avec un changement d'image
            $recruitAdvertisement->setBannerFile(null);

            return $this->redirectToRoute('app_roster_detail',[ 'id' => $recruitAdvertisement->getId()]);
        }

        $recruitAdvertisement->setBannerFile(null);

        return $this->render('roster/edit.html.twig', [
            'form'=>$form->createView(),
            'jobs' => $jobs,
            'recruitAdvertisement' => $recruitAdvertisement
        ]);
    }
    
    #[Route('/delete/{id<\d+>}', name: 'app_roster_delete')]
    #[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
    public function deleteAdvertise(Request $request, EntityManagerInterface $em, RecruitAdvertisement $recruitAdvertisement): Response
    {
        $user = $this->getUser();
        $submittedToken = $request->request->get('token');

        if($this->isCsrfTokenValid('delete_advertise', $submittedToken) && $user == $recruitAdvertisement->getOwner()){
            $em->remove($recruitAdvertisement);
            $em->flush();

            return $this->redirectToRoute('app_roster_index');
        }
        return $this->redirectToRoute('app_roster_detail', ['id' => $recruitAdvertisement->getId()]);
    }
}
