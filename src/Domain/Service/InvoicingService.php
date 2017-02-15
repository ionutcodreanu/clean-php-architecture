<?php

namespace CleanPhp\Invoicer\Domain\Service;

use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;

class InvoicingService
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var InvoiceFactory
     */
    private $invoiceFactory;

    /**
     * InvoicingService constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceFactory $invoiceFactory
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceFactory $invoiceFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceFactory = $invoiceFactory;
    }

    public function generateInvoices()
    {
        $invoices = [];
        $ordersToBeInvoices = $this->orderRepository->getUninvoicedOrders();
        foreach ($ordersToBeInvoices as $order) {
            $invoices[] = $this->invoiceFactory->createFromOrder($order);
        }
        return $invoices;
    }
}
