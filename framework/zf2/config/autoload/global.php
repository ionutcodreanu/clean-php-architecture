<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Application\Persistence\CustomerTable;
use Application\Persistence\InvoiceTable;
use Application\Persistence\OrderTable;
use Application\Persistence\TableGateway\TableGatewayFactory;
use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use Zend\Hydrator\ClassMethods;

return [
    'service_manager' => [
        'factories' => [
            Zend\Db\Adapter\Adapter::class => Zend\Db\Adapter\AdapterServiceFactory::class,
            CustomerTable::class => function ($serviceManager) {
                $factory = new TableGatewayFactory();
                $hydrator = new ClassMethods();

                $customerTableGateway = $factory->createGateway(
                    $serviceManager->get(Zend\Db\Adapter\Adapter::class),
                    $hydrator,
                    new Customer(),
                    'customers'
                );
                return new CustomerTable($customerTableGateway, $hydrator);
            },
            InvoiceTable::class => function ($serviceManager) {
                $factory = new TableGatewayFactory();
                $hydrator = new ClassMethods();

                $invoiceTableGateway = $factory->createGateway(
                    $serviceManager->get(Zend\Db\Adapter\Adapter::class),
                    $hydrator,
                    new Invoice(),
                    'invoices'
                );
                return new InvoiceTable($invoiceTableGateway, $hydrator);
            },
            OrderTable::class => function ($serviceManager) {
                $factory = new TableGatewayFactory();
                $hydrator = new ClassMethods();

                $ordersTableGateway = $factory->createGateway(
                    $serviceManager->get(Zend\Db\Adapter\Adapter::class),
                    $hydrator,
                    new Order(),
                    'orders'
                );
                return new OrderTable($ordersTableGateway, $hydrator);
            },
        ]
    ]
];
