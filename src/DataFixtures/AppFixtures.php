<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Entity\Trick;
use App\Entity\TrickFile;
use App\Entity\User;
use App\Sluger\Sluger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private Sluger $sluger;
    public function __construct(Sluger $sluger) {
        $this->sluger = $sluger;
    }
    public function load(ObjectManager $manager): void
    {
        $dataJsonFilePath = __DIR__ . '/Data/data.json';
        $snowboardTricks = json_decode(file_get_contents($dataJsonFilePath));
        
        
        $user = new User;
        $user->setEmail("ffabrice999@gmail.com")
        ->setUserName("teko13")
        ->setPassword("teko")
        ->setValide(true);
        $manager->persist($user);
        $groupe = new Groupe;
        $groupe->setName("A");
        $manager->persist($groupe);
        foreach($snowboardTricks as $i) {
            $trick = new Trick;
        $trick->setAuthor($user)
        ->setName($i->name)
        ->setSlug($this->sluger->slugify($trick->getName()))
        ->setDescription($i->description)
        ->setGroupeId($groupe);
        $manager->persist($trick);
        foreach($i->images as $im) {
            $trickFile = new TrickFile;
        $trickFile->setPath($im)
        ->setTrickId($trick)
        ->setTypeFile("image");
        $manager->persist($trickFile);
        }
        }
        $manager->flush();
    }
}
