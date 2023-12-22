<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route("/{slug}/comment", name:"comment", methods: ["POST"])]
    //#[IsGranted("CAN_CREATE", message:"Vous n'avez pas encors vérifier votre mail")]
    public function comment(Request $request, Trick $trick): Response
    {
        if($this->isGranted("CAN_CREATE")) {
            $comment = new Comment;
        $comment->setCreatedAt(new \DateTime)
        ->setContent($request->request->get("comment"))
        ->setAuthor($this->getUser())
        ->setTrickId($trick);
        $this->em->persist($comment);
        $this->em->flush();
        }
        else {
            $this->addFlash('alert-warning', "Veuillez valider votre compte");
        }
        return $this->redirect("/trick/".$trick->getSlug());
    }
}
