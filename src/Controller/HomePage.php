<?php
namespace App\Controller;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController {
    protected EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    #[Route("/", name:"home_page")]
    public function homePage() {
        $trickRepository = $this->em->getRepository(Trick::class);
        $tricksArray = $trickRepository->findAll();
        $tricks = array();
        foreach($tricksArray as $trick) {
            $trickImgs = $trick->getFiles();
            $tricks[] = [
                'trick' => $trick,
                "trick_img" => $trickImgs[mt_rand(0, 2)]
            ];
        }
        return $this->render("home_page.html.twig", ["tricks" => $tricks]);
    }
}