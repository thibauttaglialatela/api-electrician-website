<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // create 5 reviews
        for ($i = 0; $i < 5; ++$i) {
            $review = new Review();
            $review->setNote($faker->numberBetween(0, 6));
            $review->setmessage($faker->paragraph());
            $review->setFirstname($faker->firstName());
            $review->setLastname($faker->lastName());

            $manager->persist($review);
        }

        $manager->flush();
    }
}
