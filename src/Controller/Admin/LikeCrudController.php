<?php

namespace App\Controller\Admin;

use App\Entity\Like;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class LikeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Like::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('likedBy')->autocomplete(),
            AssociationField::new('likedPost')->autocomplete()->setRequired(false),
            AssociationField::new('likedComment')->autocomplete()->setRequired(false)
        ];
    }

}
