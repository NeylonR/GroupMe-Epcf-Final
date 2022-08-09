<?php

namespace App\Controller\Admin;

use App\Entity\RecruitAdvertisement;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
#[IsGranted('ROLE_ADMIN', message: 'Vous devez être un Admin pour accéder à cette page.')]
class RecruitAdvertisementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecruitAdvertisement::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('owner')->onlyOnIndex(),
            ImageField::new('banner')
            ->setBasePath('images/banner_roster')
            ->setUploadDir('public/images/banner_roster'),
            TextField::new('rosterName'),
            TextField::new('textContent')->hideOnIndex(),
            TextField::new('fflogLink'),
            TextField::new('discordServerLink'),
            NumberField::new('ilvl'),
            AssociationField::new('dataCenter'),
            AssociationField::new('language'),
            AssociationField::new('rosterType'),
            AssociationField::new('language'),
            AssociationField::new('day'),
            AssociationField::new('job'),
            TimeField::new('activityStart'),
            TimeField::new('activityEnd'),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
        ];
    }
    
}
