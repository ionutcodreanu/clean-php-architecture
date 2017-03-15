<?php
namespace Application\Persistence;

use Application\Persistence\DataTable\AbstractDataTable;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;

class OrderTable extends AbstractDataTable implements OrderRepositoryInterface
{
    public function getUninvoicedOrders()
    {
        //ugly code, add a flag to orders when are invoiced, is not scalable
        return $this->gateway->select('id NOT IN(SELECT order_id from invoices)');
    }
}
