<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Doctrine\ORM\EntityManager;
use App\Repository\AvisRepository;
use App\Repository\SliderRepository;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(SliderRepository $repo, AvisRepository $avisRepo): Response
    {   
        $avisSpas = $avisRepo->findby(['note' => 5, "categorie" => "Spas"], ['date_enregistrement' => 'DESC'], 1);
        $avisRestaurant = $avisRepo->findby(['note' => 5, "categorie" => "Restaurant"], ['date_enregistrement' => 'DESC'], 1);
        $avisChambres = $avisRepo->findby(['note' => 5, "categorie" => "Chambres"], ['date_enregistrement' => 'DESC'], 1);
        
        $slider = $repo->findAll();
        return $this->render('main/index.html.twig', [
            'photos' => $slider,
            'avisSpas' => $avisSpas,
            'avisRestaurant' => $avisRestaurant,
            'avisChambres' => $avisChambres,
        ]);
    }
    #[Route('/chambres', name:'chambres')]
    public function chambres(ChambreRepository $repo): Response
    {
        $chambres = $repo->findAll();
        return $this->render('main/chambres.html.twig', [
            'chambres' =>$chambres
            
        ]);
    }

    #[Route('/cartes', name:'cartes')]
    public function cartes(): Response
    {
        
        return $this->render('main/cartes.html.twig');
    }

    #[Route('/spas', name:'spas')]
    public function spas(ChambreRepository $repo)
    {
        $spas = $repo->findOneBy(["titre" => "spas"]);
        return $this->render('main/spas.html.twig', [
            "chambre" => $spas,
        ]);
    }
    #[Route('/avis/filtre', name:'avis_filtre')]
    #[Route('/avis', name:'avis')]
    public function avis(EntityManagerInterface $manager, Request $globals, AvisRepository $repo, $categorie = null)
    {
        
        if($globals->request->get('categorie') != null){
            $categorie= $globals->request->get('categorie');
        }
        $avisFiltre= $repo->findBy(["categorie" => $categorie]);
        
        $avis = $repo->findAll();

        $comment = new Avis;
        $form=$this->createForm(AvisType::class, $comment);
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setDateEnregistrement(new \DateTime);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute("avis",[
                'avis' => $avis,
                'form' => $form,
            ]);
        }

        return $this->renderForm('main/avis.html.twig',[
            'avis' => $avis,
            'form' => $form,
            'categorie'=>$categorie,
            'filtre'=> $avisFiltre
        ]);

    }
    #[Route('/actu', name:'actu')]
    public function actu()
    {
        $rss = simplexml_load_file('https://www.lhotellerie-restauration.fr/rss/actu_rss.xml?xtor=RSS-1');
        return $this->render('main/actu.html.twig', [
            'rssItems' => $rss->channel->item,
]);
    }

}
