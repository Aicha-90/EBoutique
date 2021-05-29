<?php

namespace App\DataFixtures;

use App\DataFixtures\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;

class UserFixtures extends Fixture
{
    protected $faker;

     public function load(ObjectManager $manager)
    {   
        //creation de faker
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');


        // create 8 users! Bam!
        for ($i = 0; $i < 55; $i++) {

            $mdp = password_hash("user" . $i, PASSWORD_DEFAULT);

            $user = new User();
            $user->setPassword($mdp);
            $user->setEmail($this->faker->safeEmail);
            $user->setRoles(["ROLE_USER"]);
            $user->setPseudo($this->faker->userName);
            $user->setNom($this->faker->firstName);
            $user->setPrenom($this->faker->firstName);
            $user->setSexe("f");
            $user->setAdresse($this->faker->streetAddress);
            $user->setCp($this->faker->numberBetween($min = 75000, $max = 9500));
            $user->setVille($this->faker->city);
            $manager->persist($user);
        }

        $manager->flush();
    }





    /*public function loadData(ObjectManager $manager)
    {
       

        $this->createMany(10, "user", function($num){
            $email = "user" . $num . "@yopmail.com";
            $mdp = password_hash("user" . $num, PASSWORD_DEFAULT);


            $user = (new User)->setEmail($email)
                              ->setPassword($mdp)
                              ->setRoles(["ROLE_USER"])
                              ->setPseudo($this->faker->realText(5))
                              ->setNom($this->faker->firstName)
                              ->setPrenom($this->faker->lastName)
                              ->setSexe($this->faker->randomElement(["m", "f"]))
                              ->setAdresse($this->faker->address)
                              ->setCp($this->faker->postcode)
                              ->setVille($this->faker->city);

            return $user;
        });

        

        $manager->flush();
    }*/
}
