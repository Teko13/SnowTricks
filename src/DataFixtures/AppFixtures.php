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
        $snowboardTricks = [
            [
                'name' => 'Ollie',
                'description' => 'A basic snowboarding trick where the rider and board leap into the air without the use of the rider\'s hands.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Nollie',
                'description' => 'Similar to an Ollie, but the rider uses the nose of the snowboard to leap instead of the tail.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Tail Press',
                'description' => 'The rider presses the tail of the snowboard down while lifting the nose, sliding on just the tail.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Nose Press',
                'description' => 'The opposite of a Tail Press, with the nose of the snowboard pressed down and the tail lifted.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Indy',
                'description' => 'A grab where the rider reaches towards the toe edge with the rear hand and grabs the board between the bindings.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Stalefish',
                'description' => 'A grab where the rider reaches behind the front leg with the rear hand to grab the heel edge between the bindings.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Tail Grab',
                'description' => 'The rider grabs the tail of the snowboard with the rear hand.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Method',
                'description' => 'A stylish grab where the rider grabs the heel edge with the leading hand and tweaks the board to the side.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Nose Grab',
                'description' => 'The rider grabs the nose of the snowboard with the leading hand.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ],
            [
                'name' => 'Wildcat',
                'description' => 'A type of backflip where the rider flips backward with the snowboard parallel to the slope.',
                'images' => [
                    'https://images.pexels.com/photos/848599/pexels-photo-848599.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'https://images.pexels.com/photos/38242/pexels-photo-38242.jpeg?auto=compress&cs=tinysrgb&w=800',
                    'https://images.pexels.com/photos/848612/pexels-photo-848612.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
                ]
            ]
        ];
        
        
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
        ->setName($i["name"])
        ->setSlug($this->sluger->slugify($trick->getName()))
        ->setDescription($i["description"])
        ->setGroupeId($groupe);
        $manager->persist($trick);
        foreach($i["images"] as $im) {
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
