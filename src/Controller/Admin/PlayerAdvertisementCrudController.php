<?php

namespace App\Controller\Admin;

use App\Entity\PlayerAdvertisement;
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
class PlayerAdvertisementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PlayerAdvertisement::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('owner')->onlyOnIndex(),
            ImageField::new('banner')
            ->setBasePath('images/banner_player')
            ->setUploadDir('public/images/banner_player'),
            TextField::new('textContent')->hideOnIndex(),
            TextField::new('fflogLink'),
            TextField::new('ffxivLink'),
            NumberField::new('ilvl'),
            AssociationField::new('homeWorld'),
            AssociationField::new('language'),
            AssociationField::new('playerType'),
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
