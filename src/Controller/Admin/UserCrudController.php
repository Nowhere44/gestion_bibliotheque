<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Repository\BibliothequeRepository;


class UserCrudController extends AbstractCrudController
{

    private $bibliothequeRepository;

    public function __construct(BibliothequeRepository $bibliothequeRepository)
    {
        $this->bibliothequeRepository = $bibliothequeRepository;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $bibliotheques = $this->bibliothequeRepository->findAll();

        $bibliothequeChoices = [];
        foreach ($bibliotheques as $bibliotheque) {
            $bibliothequeChoices[$bibliotheque->getName()] = $bibliotheque;
        }
        return [
            IdField::new('id'),
            TextField::new('username'),
            EmailField::new('email'),
            TextField::new('password')->hideOnIndex(),
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'Bibliothécaire' => 'ROLE_LIBRARIAN',
                    'Client' => 'ROLE_CLIENT'
                ]),
                AssociationField::new('bibliotheque')
                ->setLabel('Bibliotheque')
                ->setRequired(true)
           

        ];
    }
}
