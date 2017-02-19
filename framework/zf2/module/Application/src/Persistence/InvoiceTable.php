<?php
namespace Application\Persistence;

use Application\Persistence\DataTable\AbstractDataTable;
use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;

class InvoiceTable extends AbstractDataTable implements InvoiceRepositoryInterface
{
}
