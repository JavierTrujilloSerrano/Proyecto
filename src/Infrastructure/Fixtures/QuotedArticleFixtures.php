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
        $this->faker = Factory::create('es_ES');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $name = $this->faker->words(4, true);

            if (false === \is_string($name)) {
                throw new \RuntimeException('Name is not a string');
            }

            $quotedArticle = QuotedArticle::createFromAllParams(
                Uuid::v4(),
                $name,
                $this->faker->randomFloat(8, 0.00000001, 1.08000000),
                $this->faker->numberBetween(100, 5000),
            );
            $manager->persist($quotedArticle);
        }

        $manager->flush();
    }
}
