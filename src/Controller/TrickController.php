<?php
namespace App\Controller;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class TrickController extends AbstractController {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/trick/{slug}',name: "trick_show")]
    function show(Trick $trick): Response
    {
        return $this->render("details.html.twig", compact("trick"));
    }
    #[Route("/edit/trick/{id}", name:"trick_edit")]
    public function edit(Trick $trick): Response
    {
        $this->denyAccessUnlessGranted('CAN_EDIT', $trick, "Vous n'avez pas le droit d'accéder à cette ressource");
        dd("test");
        return new Response("");
    }
}
