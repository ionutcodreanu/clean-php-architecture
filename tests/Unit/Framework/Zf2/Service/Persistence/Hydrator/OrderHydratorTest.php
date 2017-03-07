<?php

namespace CleanPhp\Invoicer\Tests\Unit\Framework\Zf2\Service\Persistence\Hydrator;

use Application\Persistence\Hydrator\OrderHydrator;
use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use Zend\Hydrator\ClassMethods;

class OrderHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $customerRepository;

    protected function setUp()
    {
        $this->customerRepository = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->getMock();
    }


    /**
     * Given I hydrate with an id and an order number and a description and a total should receive specific order
     */
    public function testGivenIHydrateWithAnIdAndAnOrderNumberAndADescriptionAndATotalShouldReceiveSpecificOrder()
    {
        $hydrator = new OrderHydrator(new ClassMethods(), $this->customerRepository);
        $orderNumber = 'dummy';
        $orderId = 100;
        $dummyDescription = 'dummy description';
        $orderTotal = 1000;

        $data = [
            'id' => $orderId,
            'order_number' => $orderNumber,
            'description' => $dummyDescription,
            'total' => $orderTotal
        ];
        $order = new Order();
        $hydrator->hydrate($data, $order);
        static::assertInstanceOf(Order::class, $order);

        static::assertEquals($orderId, $order->getId());
        static::assertEquals($orderNumber, $order->getOrderNumber());
        static::assertEquals($dummyDescription, $order->getDescription());
        static::assertEquals($orderTotal, $order->getTotal());
        static::assertNull($order->getCustomer());
    }


    /**
     * Given I hydrate an order with a valid customer should receive an order with expected entity
     */
    public function testGivenIHydrateAnOrderWithAValidCustomerShouldReceiveAnOrderWithExpectedEntity()
    {
        $customer = new Customer();
        $customerId = 500;
        $customer->setId($customerId);

        $this->customerRepository
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);

        $orderHydrator = new OrderHydrator(new ClassMethods(), $this->customerRepository);
        $data = [
            'customer_id' => $customerId
        ];

        $order = new Order();
        $orderHydrator->hydrate($data, $order);
        static::assertInstanceOf(Customer::class, $order->getCustomer());
        static::assertEquals($customerId, $order->getCustomer()->getId());
    }
}
