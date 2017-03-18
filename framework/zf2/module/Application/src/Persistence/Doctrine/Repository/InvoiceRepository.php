<?php
namespace Application\Persistence\Doctrine\Repository;

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;

class InvoiceRepository extends AbstractDoctrineRepository implements InvoiceRepositoryInterface
{
    protected $entityClass = Invoice::class;
}
