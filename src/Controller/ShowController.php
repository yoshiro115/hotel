<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    #[Route('/show', name: 'app_show')]
    public function index(): Response
    {
        return $this->render('show/index.html.twig', [
            'controller_name' => 'ShowController',
        ]);
    }
    #[Route('/show/chambre/{id}', name: 'show_chambre')]
    public function chambre(ChambreRepository $repo, $id): Response
    {
        $chambre = $repo->find($id);
        
        return $this->render('show/chambre.html.twig', [
            'chambre' => $chambre,
        ]);
    }
}
