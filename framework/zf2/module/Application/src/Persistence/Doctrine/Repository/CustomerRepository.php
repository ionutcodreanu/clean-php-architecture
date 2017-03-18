<?php
namespace Application\Persistence\Doctrine\Repository;

use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;

class CustomerRepository extends AbstractDoctrineRepository implements CustomerRepositoryInterface
{
    protected $entityClass = Customer::class;
}
