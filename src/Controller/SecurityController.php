<?php
namespace App\Controller;

use App\Entity\User;
use App\Exception\UsernameExistsException;
use App\Form\LoginType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $em;
    private AuthenticationUtils $utils;
    public function __construct(AuthenticationUtils $utils, EntityManagerInterface $em)
    {
        $this->utils = $utils;
        $this->em = $em;
    }
    #[Route("/login", name: 'security_login')]
    public function login(Request $request): Response 
    {
        $form = $this->createForm(LoginType::class);
        $formView = $form->createView();
        return $this->render('security/login.html.twig', [
            'formView'=> $formView,
            "error" => $this->utils->getLastAuthenticationError()
        ]);
    }
    #[Route('/logout', name: "security_logout")]
    public function logout() {}
}
