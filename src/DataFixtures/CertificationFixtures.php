<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Certification;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CertificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        foreach (FixtureData::CERTIFICATIONS as $key => $certificationName) {
            $certification = new Certification();
            $certification->setName($certificationName);
            $certification->setIssuedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('- 6 months', 'now')));

            if ($faker->boolean(0.3)) {
                $certification->setExpiresAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 day', '+ 1 year')));
            }

            $certification->setImage($this->getReference('Image_Certification_' . $key, Image::class));

            $manager->persist($certification);
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
