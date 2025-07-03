<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Work;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WorkFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; ++$i) {
            for ($j = 0; $j < 2; ++$j) {
                $startDate    = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 months', 'now'));
                $durationDays = $faker->numberBetween(3, 30);
                $endDate      = $startDate->modify("+{$durationDays} days");

                $work = new Work();
                $work->setDescription($faker->paragraph());
                $work->setStartDate($startDate);
                $work->setEndDate($endDate);
                $work->setClient($this->getReference('client_' . $i, Client::class));

                $manager->persist($work);

                // ajouter le chantier créé en référence pour qu'il soit associé à des images
                $this->addReference('work_' . $i . $j, $work);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
        ];
    }
}
