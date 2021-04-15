<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Register;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");


        for ($i = 0; $i < 100; $i++) {
            $city = new City();

            $city->setActive(true);
            $city->setName($faker->city);
            $city->setPostcode($faker->postcode);

            $manager->persist($city);
        }

        $manager->flush();


        for ($i = 0; $i < 100; $i++) {
            $campus = new Campus();

            $campus->setActive(true);
            $campus->setName($faker->city);

            $manager->persist($campus);
        }

        $allCity = $manager->getRepository(City::class)->findAll();

        for ($i = 0; $i < 100; $i++) {
            $location = new Location();

            $location->setActive(true);
            $location->setCity($faker->RandomElement($allCity));
            $location->setName($faker->text(20));
            $location->setRoad($faker->address);
            $location->setLatitude($faker->latitude);
            $location->setLongitude($faker->longitude);

            $manager->persist($location);
        }

        $manager->flush();
        $allCampus = $manager->getRepository(Campus::class)->findAll();
        $allLocation = $manager->getRepository(Location::class)->findAll();


        for ($i = 0; $i < 50; $i++) {

            $user = new User();
            $user->setUsername($faker->userName);
            $user->setActive($faker->boolean);
            $user->setAdministrator(false);
            $user->setCampus($faker->RandomElement($allCampus));
            $user->setEmail($faker->email);
            $user->setFirstName($faker->firstName);
            $user->setName($faker->lastName);
            $user->setPhone("0612345678");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->encoder->encodePassword($user, "azerty"));

            $manager->persist($user);
        }
            $manager->flush();

            $user = new User();
            $user->setUsername("Admin");
            $user->setActive(true);
            $user->setAdministrator(false);
            $user->setCampus($faker->RandomElement($allCampus));
            $user->setEmail("admin@test.com");
            $user->setFirstName("Gérard");
            $user->setName("Menvussat");
            $user->setPhone("0687654321");
            $user->setAdministrator(true);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->encoder->encodePassword($user, "azerty"));

            $manager->persist($user);

        $manager->flush();
        $allUser = $manager->getRepository(User::class)->findAll();


        $state = new State();
        $state->setName("En cours");
        $manager->persist($state);
        $manager->flush();

        $state = new State();

        $state->setName("Ouvert");
        $manager->persist($state);
        $manager->flush();

        $state = new State();

        $state->setName("Fermé");
        $manager->persist($state);
        $manager->flush();

        $state = new State();
        $state->setName("En création");
        $manager->persist($state);
        $manager->flush();

        $allState = $manager->getRepository(State::class)->findAll();


        for ($i = 0; $i < 100; $i++) {
            $activity = new Activity();
            $activity->setState($faker->RandomElement($allState));
            $activity->setLocation($faker->RandomElement($allLocation));
            $activity->setCampus($faker->RandomElement($allCampus));
            $activity->setManager($faker->RandomElement($allUser));
            $activity->setName($faker->text(40));
            $activity->setBeginDateTime($faker->dateTimeBetween('-2 years', '+ 6 months'));
            $activity->setDuration($faker->numberBetween(30, 1000));
            $activity->setRegistrationDeadline($faker->dateTime('now', null));
            $activity->setMaximumUserNumber($faker->numberBetween(2, 20));
            $activity->setCurrentUserNumber($faker->numberBetween(2, 20));
            $activity->setDetail($faker->text(100));
            $activity->setCancellationReason($faker->text(100));
            $activity->setActive(true);
            $manager->persist($activity);
        }
        $manager->flush();
        $allActivity = $manager->getRepository(Activity::class)->findAll();


        for ($i = 0; $i < 100; $i++) {
            $register = new Register();
            $register->setUser($faker->RandomElement($allUser));
            $register->setActivity($faker->RandomElement($allActivity));
            $register->setRegisterDate($faker->dateTimeBetween('-2 years', '+ 6 months'));
            $register->setActive(true);
            $manager->persist($register);

        }


        $manager->flush();
    }
}
