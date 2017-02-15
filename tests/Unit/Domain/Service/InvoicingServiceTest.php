<?php

namespace CleanPhp\Invoicer\Tests\Unit\Domain\Service;

use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use PHPUnit\Framework\TestCase;

class InvoicingServiceTest extends TestCase
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    protected function setUp()
    {
        $this->orderRepository = $this->getMockBuilder(OrderRepositoryInterface::class)
            ->getMockForAbstractClass();
    }

    /**
     * Generate invoice for no orders return empty result
     */
    public function testGenerateInvoiceForNoOrdersReturnEmptyResult()
    {
        $this->orderRepository->method('getUninvoicedOrders')->willReturn([]);
        $invoicingService = new InvoicingService($this->orderRepository, new InvoiceFactory());
        $invoices = $invoicingService->generateInvoices();
        static::assertEquals([], $invoices);
    }

    /**
     * Generate invoices for 2 orders will return 2 invoices
     */
    public function testGenerateInvoicesFor2OrdersWillReturn2Invoices()
    {
        $this->orderRepository->method('getUninvoicedOrders')->willReturn([new Order(), new Order()]);
        $invoicingService = new InvoicingService($this->orderRepository, new InvoiceFactory());
        $invoices = $invoicingService->generateInvoices();
        static::assertCount(2, $invoices);
    }
}
