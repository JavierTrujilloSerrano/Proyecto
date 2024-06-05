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

    //usamos la librería Faker que es para generar datos aleatorios en un idioma específico
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('es_ES');
    }

    public function load(ObjectManager $manager): void
    {
        //creamos a mano dos usuarios con diferentes roles de acceso a la plataforma
        $user = User::build(
            Uuid::v4(),
            'javi@gmail.com',
            'Javier Trujillo',
            [User::ADMIN_ROLE]
        );
        $user1 = User::build(
            Uuid::v4(),
            'jose@gmail.com',
            'Jose Trujillo',
            [User::USER_ROLE]
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, '11223344'));
        $manager->persist($user);

        //con persist indicamos los datos que queremos persistir y que luego enviaremos con flush a la base de datos
        $user1->setPassword($this->passwordHasher->hashPassword($user1, '11223344'));
        $manager->persist($user1);


        //usando Faker vamos a crear datos de usuarios aleatorios pero con sentido ya que le indicamos que queremos
        // un email real pero inventado $this->faker->email incluso generamos una contraseña de 8 a 10 caracteres
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
        //Método flush grabamos en la base de datos gracias a doctrine
        $manager->flush();
    }

}