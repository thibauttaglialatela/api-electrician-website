<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Partner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PartnerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        foreach (FixtureData::PARTNERS as $key => $name) {
            $partner = new Partner();
            $partner->setName($name);
            $partner->setWebsiteUrl($faker->url());
            $partner->setImage($this->getReference('Image_Partner_' . $key, Image::class));

            $manager->persist($partner);
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
