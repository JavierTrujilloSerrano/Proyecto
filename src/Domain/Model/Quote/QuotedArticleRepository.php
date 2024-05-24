<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Quote;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Proyecto\Domain\Model\AbstractRepository;
use Symfony\Component\Uid\Uuid;

/**
 * @method QuotedArticle|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method array<QuotedArticle> findAll()
 * @method array<QuotedArticle> findBy(array $criteria, ?array $orderBy = null)
 * @method void persist(QuotedArticle $entity)
 * @method void persistAndFlush(QuotedArticle $entity)
 * @method void remove(QuotedArticle $entity)
 * @method QuotedArticle getReference(Uuid $uuid)
 */
class QuotedArticleRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuotedArticle::class);
    }

    /** @return array<string, QuotedArticle> */
    public function findAllSortedByName(): array
    {
        return $this->createQueryBuilder('qa', 'qa.id')
            ->select('qa')
            ->orderBy('LOWER(qa.name)', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
