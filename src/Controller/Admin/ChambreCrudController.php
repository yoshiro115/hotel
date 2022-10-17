<?php

namespace App\Controller\Admin;

use App\Entity\Chambre;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ChambreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chambre::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('description_courte'),
            TextareaField::new('description_longue')->setMaxLength(20),
            DateTimeField::new('date_enregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
            TextField::new('photo'),
            MoneyField::new('prix_journalier')->setCurrency('EUR'),
            
        ];
    }
    public function createEntity(string $entityFqcn)
    {
        $produit =new $entityFqcn;
        $produit->setDateEnregistrement(new \DateTime);
        return $produit;
    }
    
    
}
