<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RequestResetPasswordType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPassword extends AbstractController
{
    private HandlResetPw $handlResetPw;
    private HandlRequestResetPwForm $handlRequestResetPwForm;
    private EntityManagerInterface $em;
    public function __construct(HandlResetPw $handlResetPw, HandlRequestResetPwForm $handlRequestResetPwForm, EntityManagerInterface $em) {
        $this->handlRequestResetPwForm = $handlRequestResetPwForm;
        $this->handlResetPw = $handlResetPw;
        $this->em = $em;
    }
    #[Route("/request/reset-pw", name: "request_reset_pw")]
   public function requestResetPw(Request $request): Response
   {
    $requestResetForm = $this->createForm(RequestResetPasswordType ::class);
    $requestResetForm->handleRequest($request);
    if($requestResetForm->isSubmitted()) {
       return $this->handlRequestResetPwForm->handlRequestResetPwForm($request);
    }
    return $this->render("request_reset_pw.html.twig", [
        "formView" => $requestResetForm->createView(),
    ]);
   }
   #[Route("/reset-pw", name: "reset_pw_form")]
   public function resetPwForm(Request $request): Response
   {
    
    $token = $request->query->get("token");
    $userRepository = $this->em->getRepository(User::class);
    $user = $userRepository->findOneBy(["reset_token" => $token]);
    if($user && ($user->getExpireDate() > new \DateTime())) {
        $resetForm = $this->createForm(ResetPasswordType::class);
        $resetForm->handleRequest($request);
        if($resetForm->isSubmitted() && $resetForm->isValid()) {
            return $this->handlResetPw->handlResetPwForm($user, $request);
        }
        return $this->render("reset_pw.html.twig", ["formView" => $resetForm->createView()]);
    }
    else {
        $this->addFlash("alert-warning", "Lien expiré ou invalide");
    }
    return $this->redirect("/");
   }
   
}
