<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Groupe;
use App\Entity\Trick;
use App\Entity\TrickFile;
use App\Entity\User;
use App\Entity\UserFile;
use App\Sluger\Sluger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private Sluger $sluger;
    public function __construct(Sluger $sluger, UserPasswordHasherInterface $hasher) {
        $this->sluger = $sluger;
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $dataJsonFilePath = __DIR__ . '/Data/data.json';
        $snowboardTricks = json_decode(file_get_contents($dataJsonFilePath));
        
        
        $admin = new User;
        $admin->setEmail("admin@gmail.com")
        ->setUserName("admin")
        ->setPassword($this->hasher->hashPassword($admin, "admin"))
        ->setValide(true)
        ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);
        $userFile = new UserFile;
        $userFile->setPath("https://images.pexels.com/photos/2686914/pexels-photo-2686914.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2")
        ->setUserId($admin);
        $manager->persist($userFile);
        $user = new User;
        $user->setEmail("ffabrice999@gmail.com")
        ->setUserName("teko13")
        ->setPassword($this->hasher->hashPassword($user, "teko"))
        ->setValide(true);
        $manager->persist($user);
        $userFile = new UserFile;
        $userFile->setPath("https://images.pexels.com/photos/2686914/pexels-photo-2686914.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2")
        ->setUserId($user);
        $manager->persist($userFile);
        $user1 = new User;
        $user1->setEmail("user1@gmail.com")
        ->setUserName("user1")
        ->setPassword($this->hasher->hashPassword($user, "user"))
        ->setValide(true);
        $manager->persist($user1);
        $userFile = new UserFile;
        $userFile->setPath("https://images.pexels.com/photos/2686914/pexels-photo-2686914.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2")
        ->setUserId($user1);
        $manager->persist($userFile);
        $groupe = new Groupe;
        $groupe->setName("A");
        $manager->persist($groupe);
        foreach($snowboardTricks as $i) {
            $trick = new Trick;
        $trick->setAuthor($user)
        ->setName($i->name)
        ->setSlug($this->sluger->slugify($trick->getName()))
        ->setDescription($i->description)
        ->setGroupeId($groupe)
        ->setCreatedAt(new \DateTime)
        ->setUpdateAt(new \DateTime);
        $manager->persist($trick);
        for($c = 0; $c < count($i->images); $c++) {
            $trickFile = new TrickFile;
        $trickFile->setPath($i->images[$c])
        ->setTrickId($trick)
        ->setTypeFile("image")
        ->setFeaturedImage($c === 0);
        if($c > 4) {
            $trickFile->setTypeFile("video");
        }
        $manager->persist($trickFile);
        }
        for($cmt = 0; $cmt < 10; $cmt++) {
            $comment = new Comment;
            $comment->setAuthor($user)
            ->setCreatedAt(new \DateTime)
            ->setTrickId($trick)
            ->setContent("lorem smklkdl sdmnkcdn ejkldczkc dklsjcsklsmkl dsmlkjdslkcdsq smjklcq dsmjcjklqdmilkjc sdkjcqksmldj ksmdjcj skl");
            $manager->persist($comment);
        }
        }
        $manager->flush();
    }
}


