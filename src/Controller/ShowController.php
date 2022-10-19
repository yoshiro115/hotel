<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\ContactType;
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
    #[Route('/contact', name:'contact')]
    #[Route('/qui-sommes-nous', name:'who')]
    public function who(Request $globals, $contact = null): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($globals);
        if($form->isSubmitted() && $form->isValid())
        { 
            $this->addFlash('success', "votre demande de contact a bien été accepté");
            return $this->redirectToRoute('app_main');
        }
        $routeName = $globals->get('_route');
        if($routeName == 'contact')
        {
            $contact = 1;
        }
        
        return $this->renderForm('show/who.html.twig', [
            'contact' => $form,
            'affiche' =>$contact
        ]);
        
    }
    #[Route('/acces', name: 'acces')]
    public function acces(): Response
    {
        return $this->render('show/acces.html.twig');
    }

    #[Route('/newsletter/{route}', name:'newsletter')]
    public function newsletter(Request $globals, $route)
    {   
        //  dd($globals->request->get('newsletter'));
        if($globals->request->get('newsletter') != null){
            $this->addFlash('success', "vous êtes maintenant inscrit a la newsletter");
            return $this->redirectToRoute($route);
        }
        return $this->redirectToRoute($route);
    }
}
