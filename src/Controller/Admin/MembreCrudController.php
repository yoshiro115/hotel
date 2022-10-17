<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MembreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Membre::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('email'),
            TextField::new('pseudo'),
            TextField::new('prenom', "Prénom"),
            TextField::new('nom', 'nom de famille'),
            TextField::new('password', "mot de passe")->setFormType(PasswordType::class)->onlyWhenCreating(),
            CollectionField::new('roles')->setTemplatePath('admin/field/roles.html.twig'),
            ChoiceField::new('civilite')->setChoices(['H'=>'homme', "F"=>"femme"]),
            DateTimeField::new('date_enregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //$entityInstance correspond au $user
        if(!$entityInstance->getId())
        {
            $entityInstance->setPassword(
                $this->hasher->hashPassword(
                    $entityInstance, $entityInstance->getPassword()
                )
                );
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    
}
public function createEntity(string $entityFqcn)
    {
        $membre =new $entityFqcn;
        $membre->setDateEnregistrement(new DateTime);
        return $membre;
    }
}
    

