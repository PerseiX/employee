<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Query;

use App\Payment\Application\Query\RemunerationReports\Filter;
use App\Payment\Application\Query\RemunerationReports\Order;
use App\Payment\Application\Query\RemunerationReports\RemunerationReportQueryInterface;
use App\Payment\Domain\Model\RemunerationReport;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineRemunerationReportQuery implements RemunerationReportQueryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    public function execute(Filter $filter, Order $order): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select(
            'rr.id',
            'rr.name',
            'rr.surname',
            'rr.department',
            'rr.remuneration',
            'rr.bonus',
            'rr.bonusType',
            'rr.finalSalary'
        )
            ->from(RemunerationReport::class, 'rr');

        if (null !== $filter->name) {
            $qb->andWhere('rr.name = :name')
                ->setParameter('name', $filter->name);
        }

        if (null !== $filter->surname) {
            $qb->andWhere('rr.surname = :surname')
                ->setParameter('surname', $filter->surname);
        }

        if (null !== $filter->department) {
            $qb->andWhere('rr.department = :department')
                ->setParameter('department', $filter->department);
        }

        $qb->addOrderBy('rr.' . $order->field, $order->direction);

        return $qb->getQuery()->getArrayResult();
    }
}
