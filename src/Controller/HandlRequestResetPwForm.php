<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;


class HandlRequestResetPwForm extends AbstractController
{
    private Environment $twig;
    private MailerInterface $mailer;
    private EntityManagerInterface $em;
    public function __construct(Environment $twig, MailerInterface $mailer, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->em = $em;
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
}
