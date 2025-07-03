<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Work;
use App\Enum\ImageUsage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // parcourir les chantiers et pour chaque chantier créer 4 images
        for ($i = 0; $i < 5; ++$i) {
            for ($j = 0; $j < 2; ++$j) {
                $work = $this->getReference('work_' . $i . $j, Work::class);

                // création de 4 images
                for ($k = 0; $k < 4; ++$k) {
                    $image = new Image();
                    $image->setUrl('https://picsum.photos/seed/' . uniqid() . '/300/200');
                    $image->setAlt($faker->realText(20));
                    $image->setUsageType(ImageUsage::WORK);
                    $image->setWork($work);

                    $manager->persist($image);
                }
            }
        }

        $manager->flush();

        // créer les images associés aux prestations une par prestation

        foreach (FixtureData::PRESTATIONS as $key => $prestationName) {
            $image = new Image();
            $image->setUrl('https://picsum.photos/seed/' . uniqid() . '/300');
            $image->setAlt($faker->realText(20));
            $image->setUsageType(ImageUsage::PRESTATION);

            $manager->persist($image);

            $this->addReference('Image_Prestation_' . $key, $image);
        }

        $manager->flush();

        // créer les images associés aux certifcations
        foreach (FixtureData::CERTIFICATIONS as $key => $certificationName) {
            $image = new Image();
            $image->setUrl('https://picsum.photos/seed/' . uniqid() . '/200');
            $image->setAlt($faker->realText(20));
            $image->setUsageType(ImageUsage::CERTIFICATION);

            $manager->persist($image);

            $this->addReference('Image_Certification_' . $key, $image);
        }

        $manager->flush();

        // creation d'image associée aux partenaire
        foreach (FixtureData::PARTNERS as $key => $name) {
            $image = new Image();
            $image->setUrl('https://picsum.photos/seed/' . uniqid() . '/300');
            $image->setAlt($faker->realText(20));
            $image->setUsageType(ImageUsage::PARTNER);

            $manager->persist($image);

            $this->addReference('Image_Partner_' . $key, $image);
        }
    }

    public function getDependencies(): array
    {
        return [
            WorkFixtures::class,
        ];
    }
}
