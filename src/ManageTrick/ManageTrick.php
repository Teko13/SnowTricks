<?php

namespace App\ManageTrick;

use App\Entity\Trick;
use App\Sluger\Sluger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ManageTrick extends AbstractController
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
        $trick->setName($formData->getName())
            ->setDescription($formData->getDescription())
            ->setSlug($this->sluger->slugify($formData->getName()))
            ->setCreatedAt(new \DateTime)
            ->setUpdateAt(new \DateTime)
            ->setGroupeId($formData->getGroupeId())
            ->setAuthor($this->getUser());

        if (!$trick->getFeaturedImage()) {
            $trickFiles = $trick->files()->toArray();
            // If featuredImage is not set choose first image in trick files.
            foreach($trickFiles as $file) {
                if($file->getTypeFile() === "image") {
                    //replace file
                    $trick->removeFile($file);
                    $featuredImage = $file->setFeaturedImage(true);
                    $trick->addFile($featuredImage);
                }
            }
            // if not image return error flash message 
            if(!$trick->getFeaturedImage()) {
            $this->addFlash("alert-waring", "Vous devez enregistrer au moins une image au figure");
            return $this->redirect("/create/trick");
            }
        }

        $trickRepos = $this->em->getRepository(Trick::class);

        if ($trickRepos->findBy(["name" => $trick->getName()])) {
            $session->set("trick", $trick);
            $this->addFlash("alert-waring", "Cette figure existe déjà");
            return $this->redirect("/create/trick");
        }

        $this->em->persist($trick);
        $this->em->flush();

        $session->remove("trick");
        $this->addFlash("alert-success", "La figure a bien été créée");
        return $this->redirect("/");
    }
    public function updateTrick(Trick $trick, Trick $formData, Session $session): Response
    {
        $trickRepos = $this->em->getRepository(Trick::class);
        $updateTrick = $trickRepos->findOneBy(["id"=>$trick->getId()]);
        $updateTrick->setName($formData->getName())
            ->setDescription($formData->getDescription())
            ->setSlug($this->sluger->slugify($formData->getName()))
            ->setCreatedAt($trick->getCreatedAt())
            ->setUpdateAt(new \DateTime)
            ->setGroupeId($formData->getGroupeId())
            ->setAuthor($this->getUser());
            // add if new trick file, add them to updateTrick
            foreach($trick->files() as $file) {
                if(!in_array($file, $updateTrick->files()->toArray(), true)) {
                  $updateTrick->addFile($file);  
                }
            }
            foreach($updateTrick->files() as $file) {
                if(!in_array($file, $trick->files()->toArray(), true)) {
                    $updateTrick->removeFile($file);
                }
            }

        if (!$updateTrick->getFeaturedImage()) {
            $trickFiles = $updateTrick->files()->toArray();
            // If featuredImage is not set choose first image in trick files.
            foreach($trickFiles as $file) {
                if($file->getTypeFile() === "image") {
                    //replace file
                    $updateTrick->removeFile($file);
                    $featuredImage = $file->setFeaturedImage(true);
                    $updateTrick->addFile($featuredImage);
                }
            }
            // if no image return error flash message 
            if(!$updateTrick->getFeaturedImage()) {
                $session->set("trick", $trick);
            $this->addFlash("alert-waring", "Vous devez enregistrer au moins une image au figure");
            return $this->redirect("/edit/trick/".$updateTrick->getSlug());
            }
        }
        $this->em->persist($updateTrick);
        $this->em->flush();

        $session->remove("trick_edition");
        $this->addFlash("alert-success", "La figure a bien été créée");
        return $this->redirect("/");
    }
    public function deleteTrick(Trick $trick): void
    {
        $this->em->remove($trick);
        $this->em->flush();
    }
}
