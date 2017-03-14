<?php
namespace CleanPhp\Invoicer\Tests\Unit\Framework\Zf2\Service\Persistence\Hydrator;

use Application\Persistence\Hydrator\InvoiceHydrator;
use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Zend\Hydrator\ClassMethods;

class InvoiceHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InvoiceHydrator
     */
    private $hydrator;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $orderRepositoryMock;

    protected function setUp()
    {
        $this->orderRepositoryMock = $this->getMockBuilder(OrderRepositoryInterface::class)
            ->getMock();
        $this->hydrator = new InvoiceHydrator(new ClassMethods(), $this->orderRepositoryMock);
    }


    /**
     * Extract invoice should get total
     */
    public function testExtractInvoiceShouldGetTotal()
    {
        $invoice = new Invoice();
        $invoice->setTotal(10.5);
        $data = $this->hydrator->extract($invoice);
        static::assertArrayHasKey('total', $data);
        static::assertEquals(10.5, $data['total']);
    }

    /**
     * should extract a DateTime to a string
     */
    public function testShouldExtractADateTimeToAString()
    {
        $invoice = new Invoice();
        $invoice->setTotal(10.5);
        $invoice->setInvoiceDate(new \DateTime('2017-01-01'));

        $data = $this->hydrator->extract($invoice);
        static::assertArrayHasKey('invoice_date', $data);
        static::assertEquals('2017-01-01', $data['invoice_date']);
    }

    /**
     * Should perform a simple hydration on the object
     */
    public function testShouldPerformASimpleHydrationOnTheObject()
    {
        $invoice = new Invoice();
        $data = [
            'total' => 10.5
        ];

        $invoice = $this->hydrator->hydrate($data, $invoice);
        static::assertEquals(10.5, $invoice->getTotal());
    }

    /**
     * Should extract a date time from an invoice date string
     */
    public function testShouldExtractADateTimeFromAnInvoiceDateString()
    {
        $invoice = new Invoice();
        $data = [
            'total' => 10.5,
            'invoice_date' => '2017-01-01'
        ];

        $invoice = $this->hydrator->hydrate($data, $invoice);
        static::assertEquals('2017-01-01', $invoice->getInvoiceDate()->format('Y-m-d'));
    }

    /**
     * Should extract the order object
     */
    public function testShouldExtractTheOrderObject()
    {
        $invoice = new Invoice();
        $order = new Order();
        $order->setId(555);
        $invoice->setOrder($order);

        $data = $this->hydrator->extract($invoice);
        static::assertArrayHasKey('order_id', $data);
        static::assertEquals(555, $data['order_id']);
    }

    /**
     * Should hydrate with an order from order id
     */
    public function testShouldHydrateWithAnOrderFromOrderId()
    {
        $order = new Order();
        $order->setId(555);
        $this->orderRepositoryMock
            ->method('getById')
            ->with($order->getId())
            ->willReturn($order);
        $data = [
            'order_id' => $order->getId()
        ];
        $invoice = new Invoice();
        $invoice = $this->hydrator->hydrate($data, $invoice);

        static::assertInstanceOf(Order::class, $invoice->getOrder());
        static::assertEquals(555, $invoice->getOrder()->getId());
    }
}

