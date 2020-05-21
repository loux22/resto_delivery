<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Command;
use App\Entity\CommandDish;
use App\Entity\Comment;
use App\Entity\Dish;
use App\Entity\Member;
use App\Entity\Note;
use App\Entity\Restorer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=0; $i < 5; $i++) { 
            $user = new User;
            $user -> setMail("user" . $i . "@gmail.com");
            $user -> setPassword('$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI');
            $user -> setRoles(["ADMIN"]);
            $manager->persist($user);

            $admin = new Admin;
            $admin -> setUser($user);
            $manager->persist($admin);
        }
        for ($i=5; $i < 30; $i++) { 
            $user = new User;
            $user -> setMail("user" . $i . "@gmail.com");
            $user -> setPassword('$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI');
            $user -> setRoles(["RESTORER"]);
            $manager->persist($user);

            $restorer = new Restorer;
            $restorer -> setName("resto" . $i);
            $restorer -> setLogo("default.png");
            $restorer -> setAddress("28 rue du general cremer");
            $restorer -> setUser($user);
            $manager->persist($restorer);

            for ($j=0; $j < 30; $j++) { 
                $dish = new Dish;
                $dish -> setName("plat" . $j);
                $dish -> setImg("default.png");
                $dish -> setPrice(rand(0,20));
                $dish -> setRestorer($restorer);
                $manager->persist($dish);
            }
        }
        for ($i=30; $i < 60; $i++) { 
            $user = new User;
            $user -> setMail("user" . $i . "@gmail.com");
            $user -> setPassword('$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI');
            $user -> setRoles(["MEMBER"]);
            $manager->persist($user);

            $member = new Member;
            $member -> setUsername("first" . $i);
            $member -> setLastname("last" . $i);
            $member -> setAddress("10 rue de messi");
            $member -> setSold(rand(0,100));
            $member -> setUser($user);
            $manager->persist($member);

            for ($j=0; $j < 5; $j++) { 
                $command = new Command;
                $command -> setDelivery(new \DateTime());
                $command -> setPrice(0);
                $command -> setStatus(false);
                $command -> setUser($user);
                $manager->persist($command);

                $command_dish = new CommandDish;
                $command_dish -> setQuantity(rand(1,5));
                $command_dish -> setCommand($command);
                $command_dish -> setDish($dish);
                $manager->persist($command_dish);

                }
            $note = new Note;
            $note -> setNote(rand(0,5));
            $note -> setDish($dish);
            $note -> setUser($user);
            $manager->persist($note);

            $comment = new Comment;
            $comment -> setContent("trop bon");
            $comment -> setPublication(new \DateTime());
            $comment -> setUser($user);
            $comment -> setDish($dish);
            $manager->persist($comment);
            }

            
            
        
        $manager->flush();
    }
}
