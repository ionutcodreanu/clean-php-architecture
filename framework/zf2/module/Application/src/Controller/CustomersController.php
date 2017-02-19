<?php

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;

class CustomersController extends AbstractActionController
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * Customers constructor.
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function indexAction()
    {
        return [
            'customers' => $this->customerRepository->getAll()
        ];
    }
}
