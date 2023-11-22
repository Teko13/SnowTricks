<?php
namespace App\Controller;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController {
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    #[Route("/", name:"home_page")]
    public function homePage() {
        $trickRepository = $this->em->getRepository(Trick::class);
        $tricks = $trickRepository->findAll();
        return $this->render("home_page.html.twig", compact("tricks"));
    }
}
