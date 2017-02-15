<?php

use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;

describe('InvoicingService', function () {
    describe('->generateInvoices()', function () {
        beforeEach(function () {
            $this->repository = $this->getProphet()->prophesize(OrderRepositoryInterface::class);
        });
        it('It should query the repository for uninvoiced orders', function () {
            $this->repository->getUninvoicedOrders()->shouldBeCalled();
            $service = new CleanPhp\Invoicer\Domain\Service\InvoicingService($this->repository->reveal());
            $service->generateInvoices();
        });
        it('should return an Invoice for each uninvoiced order');
    });
});
