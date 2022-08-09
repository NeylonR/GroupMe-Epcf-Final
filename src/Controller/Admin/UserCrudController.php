<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
#[IsGranted('ROLE_ADMIN', message: 'Vous devez être un Admin pour accéder à cette page.')]
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            EmailField::new('email'),
            // TextField::new('password'),
            TextField::new('discordTag'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            ImageField::new('avatar')
            ->setBasePath('images/avatar_user')
            ->setUploadDir('public/images/avatar_user'),
            AssociationField::new('playerAdvertisement')->onlyOnIndex(),
            AssociationField::new('recruitAdvertisement')->onlyOnIndex(),
        ];
    }
    
}
