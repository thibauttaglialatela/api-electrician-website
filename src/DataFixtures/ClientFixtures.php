<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Client;
use App\Enum\Salutation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; ++$i) {
            $client = new Client();

            // Salutation aléatoire entre M. et Mme.
            /** @var Salutation $salutation */
            $salutation = $faker->randomElement([Salutation::MR, Salutation::MRS]);
            $client->setSalutation($salutation);

            // Genre adapté à la civilité
            $gender = Salutation::MRS === $salutation ? 'female' : 'male';

            $client->setFirstname($faker->firstName($gender));
            $client->setLastname($faker->lastName());
            $client->setEmail($faker->unique()->safeEmail());
            $client->setHasAcceptedPolicies(true);

            $manager->persist($client);
            $this->addReference('client_' . $i, $client);
        }

        $manager->flush();
    }
}
