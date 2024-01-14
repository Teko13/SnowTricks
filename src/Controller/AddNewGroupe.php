<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Form\NewGroupeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AddNewGroupe extends AbstractController 
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function addNewGroupe(Form $newGroupeForm): void
    {
        $groupeRepository = $this->em->getRepository(Groupe::class);
        $groupe = $newGroupeForm->getData();
        if(!$groupeRepository->findOneBy(["name" => $groupe->getName()])) {
            $this->em->persist($groupe);
            $this->em->flush();
            $this->addFlash("alert-success", "Le groupe ".$groupe->getName()." a bien é(é créé");
        }
        else {
            $this->addFlash("alert-warning", "Ce groupe existe déjà");
        }
    }
}
