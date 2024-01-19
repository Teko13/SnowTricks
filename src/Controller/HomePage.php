<?php
namespace App\Controller;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController {
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    #[Route("/", name:"home_page")]
    public function homePage(Request $request): Response
    {
        $trickRepository = $this->em->getRepository(Trick::class);
        //Initially, only 10 tricks are loaded. When the user clicks the button to see all the tricks, the parameter 'loadMore' will be in the URL, and all the tricks will be loaded.
        $loadAll = $request->query->get("loadAll");
        if($loadAll) {
            $tricks = $trickRepository->findAll();
        }
        else {
            $tricks = $trickRepository->findBy([], ["created_at" => "DESC"], 10);
        }
        return $this->render("home_page.html.twig", compact("tricks", "loadAll"));
    }
}
