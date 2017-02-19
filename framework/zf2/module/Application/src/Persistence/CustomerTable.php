<?php
namespace Application\Persistence;

use Application\Persistence\DataTable\AbstractDataTable;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;

class CustomerTable extends AbstractDataTable implements CustomerRepositoryInterface
{
}
