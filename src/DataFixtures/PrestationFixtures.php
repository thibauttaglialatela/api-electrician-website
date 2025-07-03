<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Prestation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PrestationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        foreach (FixtureData::PRESTATIONS as $key => $prestationName) {
            $prestation = new Prestation();
            $prestation->setName($prestationName);
            $prestation->setPrice($faker->numberBetween(20, 200));
            $prestation->setDescription($faker->realText());
            $prestation->setImage($this->getReference('Image_Prestation_' . $key, Image::class));

            $manager->persist($prestation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ImageFixtures::class,
        ];
    }
}
