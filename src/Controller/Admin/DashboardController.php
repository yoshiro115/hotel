<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Entity\Slider;
use App\Entity\Chambre;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\ChambreCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $routeBuilder;

    public function __construct(AdminUrlGenerator $routebuilder)
    {
        $this->routeBuilder = $routebuilder;
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
         // permet de rediriger vers les chambres pour ne pas afficher la page d'accueil par défaut (voir doc easyadmin)
         return $this->redirect($this->routeBuilder->setController(ChambreCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BACKOFFICE Hotel');
    }

    public function configureMenuItems(): iterable
    {
        return [
             MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
             MenuItem::section("Back to website"),
             MenuItem::linkToRoute('Accueil', 'fa fa-home', 'app_main'),
             MenuItem::section("Chambre"),
            MenuItem::linkToCrud('Chambre', 'fas fa-clipboard', Chambre::class),
            MenuItem::section('Membres'),
           MenuItem::linkToCrud('utilisateurs', 'fas fa-user', Membre::class),
           MenuItem::section('Commandes'),
           MenuItem::linkToCrud('commandes', 'fas fa-box', Commande::class),
           MenuItem::section('Slider'),
           MenuItem::linkToCrud('Slider', 'fas fa-image', Slider::class),
           
        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
