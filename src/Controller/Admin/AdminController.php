<?php
namespace App\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Controller\Admin\BibliothequeCrudController;
use App\Controller\Admin\LivreCrudController;
use App\Controller\BibliothequeController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use App\Entity\User;
use App\Entity\Bibliotheque;
use App\Entity\Livre;


class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin', ['crudAction' => 'index', 'crudControllerFqcn' => UserCrudController::class]);
    }
    

public function configureMenuItems(): iterable
{
    return [
        MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
        MenuItem::linkToCrud('Users', 'fa fa-user', User::class)->setController(UserCrudController::class),
        MenuItem::linkToCrud('Bibliotheques', 'fa fa-book', Bibliotheque::class)->setController(BibliothequeCrudController::class),
        MenuItem::linkToCrud('Livres', 'fa fa-bookmark', Livre::class)->setController(LivreCrudController::class),
    ];
}
}
