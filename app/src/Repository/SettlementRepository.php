<?php

namespace App\Repository;

use App\Entity\Settlement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Settlement>
 *
 * @method Settlement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Settlement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Settlement[]    findAll()
 * @method Settlement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettlementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Settlement::class);
    }

    /** @return Settlement[] */
    public function findDistrictSettlements(): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('s')->where($expr->isNull('s.parent'))->getQuery()->getResult();
    }

    public function removeAllSettlements(): void
    {
        $this->getEntityManager()->createQueryBuilder()->delete()->from(Settlement::class, 's')->getQuery()->execute();
    }
}
