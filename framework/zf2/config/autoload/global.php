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
use Application\Persistence\Doctrine\Repository\CustomerRepository;
use Application\Persistence\Doctrine\Repository\InvoiceRepository;
use Application\Persistence\Doctrine\Repository\OrderRepository;
use Application\Persistence\Doctrine\Repository\RepositoryFactory;
use Application\Persistence\Hydrator\InvoiceHydrator;
use Application\Persistence\Hydrator\OrderHydrator;
use Application\Persistence\InvoiceTable;
use Application\Persistence\OrderTable;
use Application\Persistence\TableGateway\TableGatewayFactory;
use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Hydrator\ClassMethods;

return [
    'service_manager' => [
        'factories' => array(
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
            OrderHydrator::class => function (ContainerInterface $container, $requestedName) {
                return new OrderHydrator(
                    new ClassMethods(),
                    $container->get(CustomerRepository::class)
                );
            },
            OrderTable::class => function (ContainerInterface $container, $requestedName) {
                $factory = new TableGatewayFactory();
                $orderHydrator = $container->get(OrderHydrator::class);
                $orderGateway = $factory->createGateway(
                    $container->get(Adapter::class),
                    $orderHydrator,
                    new Order(),
                    'orders'
                );
                return new OrderTable($orderGateway, $orderHydrator);
            },
            InvoiceHydrator::class => function (ContainerInterface $container, $requestedName) {
                return new InvoiceHydrator(
                    new ClassMethods(),
                    $container->get(OrderTable::class)
                );
            },
            InvoiceTable::class => function (ContainerInterface $container, $requestedName) {
                $factory = new TableGatewayFactory();
                $hydrator = $container->get(InvoiceHydrator::class);
                return new InvoiceTable(
                    $factory->createGateway(
                        $container->get(Zend\Db\Adapter\Adapter::class),
                        $hydrator,
                        new Invoice(),
                        'invoices'
                    ),
                    $hydrator
                );
            },
            CustomerRepository::class => RepositoryFactory::class,
            OrderRepository::class => RepositoryFactory::class,
            InvoiceRepository::class => RepositoryFactory::class,
        )
    ]
];
