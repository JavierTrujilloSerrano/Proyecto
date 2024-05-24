<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method array<User> findAll()
 * @method array<User> findBy(array $criteria, ?array $orderBy = null)
 * @method void persist(User $entity)
 * @method void persistAndFlush(User $entity)
 * @method void remove(User $entity)
 * @method void getReference($id)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u.email
                FROM users u
                WHERE u.email = :query'
        )
            ->setParameter('query', $identifier)
            ->getOneOrNullResult();
    }
}