<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RequestResetPasswordType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ResetPassword extends AbstractController
{
    private EntityManagerInterface $em;
    private Environment $twig;
    private MailerInterface $mailer;
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $em, Environment $twig, MailerInterface $mailer) {
        $this->twig = $twig;
        $this->em = $em;
        $this->hasher = $hasher;
        $this->mailer = $mailer;
    }
    public function handlRequestResetPwForm(Request $request): Response
    {
        $token = bin2hex(random_bytes(32));
        $data = $request->request->all()["request_reset_password"];
        $expireDate = new \DateTime("+1 hour");
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(["username" => $data["user_name"]]);
        $url = $this->generateUrl("reset_pw_form", array("token" => $token), UrlGeneratorInterface::ABSOLUTE_URL);
        if(!$user) {
            $this->addFlash("alert-warning", "Aucun utilisateur n'a été trouvé");
            return $this->redirect("/request/reset-pw");
        }
        $user->setExpireDate($expireDate)->setResetToken($token);
        $this->em->persist($user);
        $this->em->flush();
        $email = (new Email())
        ->from(new Address("ffabrice999@gmail.com", "SnowTricks"))
        ->to($user->getEmail())
        ->subject("Mot de passe oublié")
        ->html($this->twig->render("reset_pw_email_template.html.twig", ["user" => $user, "url" => $url]));
        try {
            $this->mailer->send($email);
            $this->addFlash("alert-success", "Un mail a été envoyé à votre adresse e-mail pour modifier votre mot de passe");
            return $this->redirect("/");
        } catch (TransportExceptionInterface $e) {
            $this->addFlash("alert-warning", "Un probleme est survenue.");
            return $this->redirect("/request/reset-pw");
        }
    }
    public function handlResetPwForm(User $user, Request $request): Response
    {
        $data = $request->request->all();
            $plainPassword = $data["reset_password"]["plainPassword"];
            $user->setPassword($this->hasher->hashPassword($user, $plainPassword))
            ->setExpireDate(null)
            ->setResetToken(null);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash("alert-success", "Le mot de passe de votre compte a bien été modifié");
            return $this->redirect("/login");
    }
    #[Route("/request/reset-pw", name: "request_reset_pw")]
   public function requestResetPw(Request $request): Response
   {
    $requestResetForm = $this->createForm(RequestResetPasswordType ::class);
    $requestResetForm->handleRequest($request);
    if($requestResetForm->isSubmitted()) {
       return $this->handlRequestResetPwForm($request);
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
            return $this->handlResetPwForm($user, $request);
        }
        return $this->render("reset_pw.html.twig", ["formView" => $resetForm->createView()]);
    }
    else {
        $this->addFlash("alert-warning", "Lien expiré ou invalide");
    }
    return $this->redirect("/");
   }
}
