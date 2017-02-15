<?php

namespace CleanPhp\Invoicer\Tests\Unit\Domain\Factory;

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;
use PHPUnit\Framework\TestCase;

class InvoiceFactoryTest extends TestCase
{
    /**
     * @var InvoiceFactory
     */
    protected $invoiceFactory;

    protected function setUp()
    {
        $this->invoiceFactory = new InvoiceFactory();
    }

    /**
     * Create from order should return an invoice object
     */
    public function testCreateFromOrderShouldReturnAnInvoiceObject()
    {
        $order = new Order();
        $invoice = $this->invoiceFactory->createFromOrder($order);
        static::assertInstanceOf(Invoice::class, $invoice);
    }


    /**
     * Create From Order with a total of 10 should return an invoice with total of 10
     */
    public function testCreateFromOrderWithATotalOf10ShouldReturnAnInvoiceWithTotalOf10()
    {
        $order = new Order();
        $order->setTotal(10);
        $invoice = $this->invoiceFactory->createFromOrder($order);
        static::assertEquals(10, $invoice->getTotal());
    }


    /**
     * Create from order should associate order to invoice
     */
    public function testCreateFromOrderShouldAssociateOrderToInvoice()
    {
        $order = new Order();
        $invoice = $this->invoiceFactory->createFromOrder($order);
        static::assertEquals($order, $invoice->getOrder());
    }


    /**
     * Create from order should set the date to invoice
     */
    public function testCreateFromOrderShouldSetTheDateToInvoice()
    {
        $order = new Order();
        $invoice = $this->invoiceFactory->createFromOrder($order);
        $invoiceDate = $invoice->getInvoiceDate()->format('Y-m-d H:i:s');
        $currentDate = (new \DateTime())->format('Y-m-d H:i:s');
        static::assertEquals($currentDate, $invoiceDate);
    }
}
