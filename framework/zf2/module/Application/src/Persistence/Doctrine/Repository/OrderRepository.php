<?php
namespace Application\Persistence\Doctrine\Repository;

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;

class OrderRepository extends AbstractDoctrineRepository implements OrderRepositoryInterface
{
    protected $entityClass = Order::class;

    public function getUninvoicedOrders()
    {
        $builder = $this->entityManager->createQueryBuilder()
            ->select('o')
            ->from($this->entityClass, 'o')
            ->leftJoin(Invoice::class, 'i', Join::WITH, 'i.order = o')
            ->where('i.id IS NULL');

        return $builder->getQuery()->getResult();
    }
}
