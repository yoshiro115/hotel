<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiversController extends AbstractController
{
    #[Route('/divers', name: 'app_divers')]
    public function index(): Response
    {
        return $this->render('divers/index.html.twig', [
            'controller_name' => 'DiversController',
        ]);
    }
    #[Route('/legacy', name: 'legacy')]
    public function legacy(): Response
    {
        return $this->render('divers/legacy.html.twig');
    }
    #[Route('/vente', name: 'vente')]
    public function vente(): Response
    {
        return $this->render('divers/vente.html.twig');
    }
    #[Route('/plan', name: 'plan')]
    public function plan(): Response
    {
        return $this->render('divers/plan.html.twig');
    }
}
