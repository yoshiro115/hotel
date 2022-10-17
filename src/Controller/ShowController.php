<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function chambre(ChambreRepository $repo, $id, Request $globals, EntityManagerInterface $manager): Response
    {
        $chambre = $repo->find($id);

        $commande = new Commande;
        
        $form = $this->createForm(CommandeType::class, $commande );

        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        { 
            $depart = $commande->getDateArrivee();
            $fin = $commande->getDateDepart();
            $interval = $depart->diff($fin);
            $days = $interval->days;
            $commande->setDateEnregistrement(new \DateTime);
            $prix = $chambre->getPrixJournalier();
            $prix = $prix * $days;
            $commande->setPrixTotal($prix);
            $commande->setChambre($chambre);
            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', "votre commande a bien été accepté");
            return $this->redirectToRoute('app_main');
        }
        return $this->renderForm('show/chambre.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }
}
