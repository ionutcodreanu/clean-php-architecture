<?php
namespace Application\Persistence;

use Application\Persistence\DataTable\AbstractDataTable;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;

class OrderTable extends AbstractDataTable implements OrderRepositoryInterface
{
    public function getUninvoicedOrders()
    {
        // TODO: Implement getUninvoicedOrders() method.
    }
}
