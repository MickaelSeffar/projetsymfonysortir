<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");




     //$allUsers = $manager->getRepository(User::class)->findAll();







        for ($i = 0; $i<100; $i++) {
            $city = new City();

            $city->setActive(true);
            $city->setName($faker->city);
            $city->setPostcode($faker->postcode);

            $manager->persist($city);
        }


        for ($i = 0; $i<100; $i++) {
            $campus = new Campus();

            $campus->setActive(true);
            $campus->setName($faker->city);

            $manager->persist($campus);
        }

        for ($i = 0; $i<100; $i++) {
            $location = new Location();

            $allCity = $manager->getRepository(City::class)->findAll();

            $location->setActive(true);
            $location->setCity($faker->RandomElement($allCity));
            $location->setName($faker->text(20));
            $location->setRoad($faker->address);
            $location->setLatitude($faker->latitude);
            $location->setLongitude($faker->longitude);

            $manager->persist($location);
        }







        $allCampus = $manager->getRepository(Campus::class)->findAll();


        $user = new User();
        $user->setName("Utilisateur");
        $user->setActive(true);
        $user->setAdministrator(false);
        $user->setCampus($faker->RandomElement($allCampus));
        $user->setEmail("user@test.com");
        $user->setFirstName("Camille");
        $user->setName("Onette");
        $user->setPhone("0612345678");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword("azerty");

        $user = new User();
        $user->setUsername("Admin");
        $user->setActive(true);
        $user->setAdministrator(false);
        $user->setCampus($faker->RandomElement($allCampus));
        $user->setEmail("admin@test.com");
        $user->setFirstName("Gérard");
        $user->setName("Menvussat");
        $user->setPhone("0687654321");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword("azerty");

        $manager->persist($user);


        for ($i = 0; $i<100; $i++) {
            $state = new State();
            $state->setName("En cours");
            $state->setName("Ouvert");
            $state->setName("Fermé");

            $manager->persist($state);

        }


            $manager->flush();
    }
}
