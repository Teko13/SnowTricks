<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class HandlResetPw extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $em;
    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $em) {
        $this->em = $em;
        $this->hasher = $hasher;
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
}
