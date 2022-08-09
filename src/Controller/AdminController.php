<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Entity\Language;
use App\Entity\HomeWorld;
use App\Form\LanguageType;
use App\Repository\DataCenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/adminOld')]
#[IsGranted('ROLE_ADMIN', message: 'Vous devez être un Admin pour accéder à cette page.')]
class AdminController extends AbstractController
{

    #[Route('', name: 'app_admin')]
    public function admin(Request $request, EntityManagerInterface $em): Response
    {     
        $job = new Job(); 
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/admin.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/language', name: 'app_admin_language')]
    public function createLanguage(Request $request, EntityManagerInterface $em): Response
    {     
        $language = new Language(); 
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($language);
            $em->flush();

            return $this->redirectToRoute('app_admin_language');
        }

        return $this->render('admin/addLanguage.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/server', name: 'app_add_server')]
    public function addServer(EntityManagerInterface $em, DataCenterRepository $dcrepo): Response
    {
        // récupération du json avec l'api xivapi.com de la liste des seveurs lié à leurs data center
        $json = file_get_contents("https://xivapi.com/servers/dc?private_key=f3b4ca5fef6f44c7911e0e69e186f3dce90fa480be9f4efc8f12a5aa9906c4fe");
        $json = json_decode($json);
        // fonction d'ajout à la database
        function addServer($json, $dc, $id, $em, $dcrepo){
            foreach($json->$dc as $server){
                $serv = new HomeWorld();
                $serv->setLabel($server);
                $serv->setDataCenter($dcrepo->findOneBy(['id'=>$id]));
                $em->persist($serv);
                $em->flush();
            };
        }
        addServer($json, 'Elemental', 1, $em, $dcrepo);
        addServer($json, 'Gaia', 2, $em, $dcrepo);
        addServer($json, 'Mana', 3, $em, $dcrepo);
        addServer($json, 'Aether', 4, $em, $dcrepo);
        addServer($json, 'Primal', 5, $em, $dcrepo);
        addServer($json, 'Crystal', 6, $em, $dcrepo);
        addServer($json, 'Chaos', 7, $em, $dcrepo);
        addServer($json, 'Light', 8, $em, $dcrepo);
        addServer($json, 'Materia', 9, $em, $dcrepo);

        return $this->redirectToRoute('app_admin');
    }
}
