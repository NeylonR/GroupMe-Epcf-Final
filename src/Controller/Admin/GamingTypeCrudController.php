<?php

namespace App\Controller\Admin;

use App\Entity\GamingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
#[IsGranted('ROLE_ADMIN', message: 'Vous devez être un Admin pour accéder à cette page.')]
class GamingTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GamingType::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
