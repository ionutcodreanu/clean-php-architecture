<?php

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use Zend\Mvc\Controller\AbstractActionController;

class InvoicesController extends AbstractActionController
{
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var InvoicingService
     */
    private $invoicingService;

    /**
     * InvoicesController constructor.
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoicingService $invoicingService
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        OrderRepositoryInterface $orderRepository,
        InvoicingService $invoicingService
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->invoicingService = $invoicingService;
    }

    public function indexAction()
    {
        $invoices = $this->invoiceRepository->getAll();
        return [
            'invoices' => $invoices
        ];
    }

    public function generateAction()
    {
        return [
            'orders' => $this->orderRepository->getUninvoicedOrders()
        ];
    }

    public function generateProcessAction()
    {
        $invoices = $this->invoicingService->generateInvoices();
        foreach ($invoices as $invoice) {
            $this->invoiceRepository->persist($invoice);
        }
        return [
            'invoices' => $invoices
        ];
    }

    public function viewAction()
    {
        $invoiceId = $this->params()->fromRoute('id');
        /** @var Invoice $invoice */
        $invoice = $this->invoiceRepository->getById($invoiceId);
        if (!$invoice) {
            $this->getResponse()->setStatusCode(404);
            return null;
        }
        return [
            'invoice' => $invoice,
            'order' => $invoice->getOrder()
        ];
    }
}
