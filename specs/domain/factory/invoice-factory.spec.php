<?php

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;

describe('InvoiceFactory', function () {
    describe('->createFromOrder', function () {
        it('should return an order object', function () {
            $order = new Order();
            $factory = new InvoiceFactory();
            $invoice = $factory->createFromOrder($order);
            expect($invoice)->to->be->instanceof(Invoice::class);

        });

        it('should set the total to the invoice', function () {
            $order = new Order();
            $order->setTotal(100);
            $factory = new InvoiceFactory();
            $invoice = $factory->createFromOrder($order);
            expect($invoice->getTotal())->to->equal(100);
        });

        it('should associate the Order to the Invoice', function () {
            $order = new Order();
            $invoiceFactory = new InvoiceFactory();

            $invoice = $invoiceFactory->createFromOrder($order);

            expect($invoice->getOrder())->to->equal($order);
        });

        it('should set the date to the invoice', function () {
            $order = new Order();
            $invoiceFactory = new InvoiceFactory();

            $invoice = $invoiceFactory->createFromOrder($order);

            $invoiceDate = $invoice->getInvoiceDate()->format('Y-m-d H:i:s');
            $currentDate = (new \DateTime())->format('Y-m-d H:i:s');
            expect($invoiceDate)->to->loosely->equal($currentDate);
        });
    });
});
