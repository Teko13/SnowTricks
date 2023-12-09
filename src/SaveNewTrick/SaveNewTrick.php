<?php

namespace App\SaveNewTrick;

use App\Entity\Trick;
use App\Sluger\Sluger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SaveNewTrick extends AbstractController
{
    private EntityManagerInterface $em;
    private Sluger $sluger;

    public function __construct(EntityManagerInterface $em, Sluger $sluger)
    {
        $this->em = $em;
        $this->sluger = $sluger;
    }

    public function saveNewTrick(Trick $trick, Trick $formData, Session $session): Response
    {
        $flashBag = $session->getBag("flashes");
        $trick->setName($formData->getName())
            ->setDescription($formData->getDescription())
            ->setSlug($this->sluger->slugify($formData->getName()))
            ->setCreatedAt(new \DateTime)
            ->setUpdateAt(new \DateTime)
            ->setGroupeId($formData->getGroupeId())
            ->setAuthor($this->getUser());

        if (!$trick->getFeaturedImage()) {
            $session->set("trick", $trick);
            $flashBag->set("alert-waring", "Vous devez enregistrer au moins une image de mise en avant");
            return $this->redirect("/create/trick");
        }

        $trickRepos = $this->em->getRepository(Trick::class);

        if ($trickRepos->findBy(["name" => $trick->getName()])) {
            $flashBag = $session->getBag("flashes");
            $session->set("trick", $trick);
            $flashBag->set("alert-waring", "Cette figure existe déjà");
            return $this->redirect("/create/trick");
        }

        $this->em->persist($trick);
        $this->em->flush();

        $flashBag = $session->getFlashBag();
        $session->remove("trick");
        $flashBag->set("alert-success", "La figure a bien été créée");
        return $this->redirect("/");
    }
}
