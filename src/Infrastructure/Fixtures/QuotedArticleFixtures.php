<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Proyecto\Domain\Model\Quote\QuotedArticle;
use Symfony\Component\Uid\Uuid;

class QuotedArticleFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
    }

    //rellenamos la base de datos

    public function load(ObjectManager $manager): void
    {
            $defaultArticlesData = [
                ['Apple iPhone 13', 130.00, 180],
                ['Google Pixel 6', 109.95, 160],
                ['OnePlus 9 Pro', 115.00, 170],
                ['Sony Xperia 1', 140.00, 190],
                ['Xiaomi Mi 11 Ultra', 124.50, 175],
                ['Samsung Galaxy Note 20', 135.10, 185],
                ['LG Wing', 120.00, 165],
                ['Motorola Edge Plus', 110.90, 155],
                ['Huawei P50 Pro', 130.00, 180],
                ['Asus ROG Phone 5', 145.99, 195],
            ];

            foreach ($defaultArticlesData as $articleData) {
                $defaultArticle = QuotedArticle::createFromAllParams(
                    Uuid::v4(),
                    $articleData[0],
                    $articleData[1],
                    $articleData[2]
                );
                $manager->persist($defaultArticle);
            }
            $manager->flush();


/*
            for ($i = 0; $i < 10; $i++) {
                $name = $this->faker->words(4, true);

                if (false === \is_string($name)) {
                    throw new \RuntimeException('Name is not a string');
                }

                $quotedArticle = QuotedArticle::createFromAllParams(
                    Uuid::v4(),
                    $name,
                    $this->faker->randomFloat(2, 0.10, 5.90),
                    $this->faker->numberBetween(100, 5000),
                );
                $manager->persist($quotedArticle);
            }
            $manager->flush();
        */
    }

}
