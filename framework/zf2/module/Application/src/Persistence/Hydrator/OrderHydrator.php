<?php
namespace Application\Persistence\Hydrator;

use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use Zend\Hydrator\HydratorInterface;

class OrderHydrator implements HydratorInterface
{
    /**
     * @var HydratorInterface
     */
    private $wrappedHydrator;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * OrderHydrator constructor.
     * @param HydratorInterface $hydrator
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(HydratorInterface $hydrator, CustomerRepositoryInterface $customerRepository)
    {
        $this->wrappedHydrator = $hydrator;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param array $data
     * @param Order $order
     * @return Order
     */
    public function hydrate(array $data, $order)
    {
        $this->wrappedHydrator->hydrate($data, $order);
        $customer = null;
        if (isset($data['customer'])) {
            $customer = $this->wrappedHydrator->hydrate(
                $data['customer'],
                new Customer()
            );
            unset($data['customer']);
        }
        if (isset($data['customer_id'])) {
            $customer = $this->customerRepository->getById($data['customer_id']);
        }
        if ($customer) {
            $order->setCustomer($customer);
        }
        return $order;
    }

    public function extract($object) {
        $data = $this->wrappedHydrator->extract($object);
        if (array_key_exists('customer', $data) &&
            !empty($data['customer'])) {
            $data['customer_id'] = $data['customer']->getId();
            unset($data['customer']);
        }
        return $data;
    }}
