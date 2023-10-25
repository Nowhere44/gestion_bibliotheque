<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;  // Add this line
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;  // Add this line
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('author'),
            DateField::new('publishedDate'),  // Add this line
            TextareaField::new('description'),  // Add this line
            TextField::new('image', 'Image URL'),
            AssociationField::new('bibliotheque')->autocomplete(),

        ];
    }
}
