<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifyNameType;
use App\Form\ModifyEmailType;
use App\Form\ModifyAvatarType;
use App\Form\DeleteAccountType;
use App\Form\ModifyPasswordType;
use App\Form\ModifyDiscordTagType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profil')]
#[IsGranted('ROLE_USER', message: 'Vous devez être connecté pour accéder à cette page.')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();

        $formName = $this->createForm(ModifyNameType::class, $user);
        $formEmail = $this->createForm(ModifyEmailType::class, $user);
        $formPassword = $this->createForm(ModifyPasswordType::class, $user);
        $formDiscordTag = $this->createForm(ModifyDiscordTagType::class, $user);
        $formAvatar = $this->createForm(ModifyAvatarType::class, $user);
        $formName->handleRequest($request);
        $formEmail->handleRequest($request);
        $formPassword->handleRequest($request);
        $formDiscordTag->handleRequest($request);
        $formAvatar->handleRequest($request);

        if($formName->isSubmitted() && $formName->isValid() || $formEmail->isSubmitted() && $formEmail->isValid() || $formDiscordTag->isSubmitted() && $formDiscordTag->isValid()){
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_profil');
        }

        if($formPassword->isSubmitted() && $formPassword->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user, 
                    $formPassword->get('password')->getData()
                    )
                );
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_profil');
        }

        if($formAvatar->isSubmitted() && $formAvatar->isValid()){
            $em->persist($user);
            $em->flush();
            $this->getUser()->setAvatarFile(null);

            return $this->redirectToRoute('app_profil');
        }
        $this->getUser()->setAvatarFile(null);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'formName' => $formName->createView(),
            'formEmail' => $formEmail->createView(),
            'formPassword' => $formPassword->createView(),
            'formDiscordTag' => $formDiscordTag->createView(),
            'formAvatar' => $formAvatar->createView()
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'app_delete_account')]
    public function deleteAccount(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // $form = $this->createForm(DeleteAccountType::class, $user);
        // $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if($this->isCsrfTokenValid('delete_account', $submittedToken) && $user->getId() == $request->get('id')){
            $em->remove($user);
            $em->flush();
            $session = new Session();
            $session->invalidate();

            return $this->redirectToRoute('app_logout');
        }
        return $this->redirectToRoute('app_profil');
    }
}
