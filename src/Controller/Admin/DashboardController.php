<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\Categorie;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hotel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categories', 'fas fa-tags', Categorie::class);
        yield MenuItem::linkToCrud('Chambres', 'fa-solid fa-person-shelter', Chambre::class);
        yield MenuItem::linkToCrud('Slider', 'fa-solid fa-image', Slider::class);
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-list', Commande::class);
        yield MenuItem::linkToCrud('Avis', 'fa-solid fa-comment', Avis::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
