<?php

namespace Proyecto\Infrastructure\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Proyecto\Domain\Model\User\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;


class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('es_ES');
    }

    public function load(ObjectManager $manager): void
    {
        $user = User::build(
            Uuid::v4(),
            'javi@gmail.com',
            'Javier Trujillo',
            [User::ADMIN_ROLE]
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, '11223344'));
        $manager->persist($user);

        for ($i = 0; $i < 5; $i++) {
            $user = User::createFromAllParams(
                Uuid::v4(),
                $this->faker->email,
                $this->faker->name,
                [User::USER_ROLE]
            );
            $user->setPassword($this->faker->password(8, 10));
            $manager->persist($user);

        }
        $manager->flush();
    }

}