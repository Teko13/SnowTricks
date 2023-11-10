<?php
namespace App\Controller;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class TrickController extends AbstractController {
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/trick/{slug}',name: "trick_show")]
    function trickShow(Trick $trick): Response
    {
        return $this->render("details.html.twig", compact("trick"));
    }
}