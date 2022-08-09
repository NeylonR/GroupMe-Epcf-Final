<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Controller\Admin\UserCrudController;
use App\Entity\DataCenter;
use App\Entity\Day;
use App\Entity\GamingType;
use App\Entity\HomeWorld;
use App\Entity\Job;
use App\Entity\Language;
use App\Entity\PlayerAdvertisement;
use App\Entity\RecruitAdvertisement;
use App\Entity\Region;
use App\Entity\Role;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[IsGranted('ROLE_ADMIN', message: 'Vous devez être un Admin pour accéder à cette page.')]
class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un Admin pour accéder à cette page.')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Final Project');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Annonce de joueur', 'fas fa-list', PlayerAdvertisement::class);
        yield MenuItem::linkToCrud('Annonce de roster', 'fas fa-list', RecruitAdvertisement::class);
        yield MenuItem::linkToCrud('Job', 'fas fa-list', Job::class);
        yield MenuItem::linkToCrud('Langues', 'fas fa-list', Language::class);
        yield MenuItem::linkToCrud('Jours', 'fas fa-list', Day::class);
        yield MenuItem::linkToCrud('Role', 'fas fa-list', Role::class);
        yield MenuItem::linkToCrud('Region', 'fas fa-list', Region::class);
        yield MenuItem::linkToCrud('Centre de données', 'fas fa-list', DataCenter::class);
        yield MenuItem::linkToCrud('Serveurs', 'fas fa-list', HomeWorld::class);
        yield MenuItem::linkToCrud('Type de joueurs/roster', 'fas fa-list', GamingType::class);
    }
}
