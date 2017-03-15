<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\CustomersController;
use Application\Controller\InvoicesController;
use Application\Controller\OrdersController;
use Application\Persistence\CustomerTable;
use Application\Persistence\Hydrator\OrderHydrator;
use Application\Persistence\InvoiceTable;
use Application\Persistence\OrderTable;
use Application\Persistence\TableGateway\TableGatewayFactory;
use Application\Service\InputFilter\CustomerInputFilter;
use Application\Service\InputFilter\OrderInputFilter;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Hydrator\ClassMethods;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'customers' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/customers',
                    'defaults' => [
                        'controller' => CustomersController::class,
                        'action' => 'index',
                    ],

                ],
                'may_terminate' => true,
                'child_routes' => [
                    'create' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/new',
                            'defaults' => [
                                'action' => 'new-or-edit',
                            ],
                        ]
                    ],
                    'edit' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/edit/:id',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'action' => 'new-or-edit',
                            ],
                        ]
                    ],
                ]
            ],
            'orders' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/orders[/:action[/:id]]',
                    'defaults' => [
                        'controller' => OrdersController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'invoices' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/invoices[/:action[/:id]]',
                    'defaults' => [
                        'controller' => InvoicesController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            CustomersController::class => function (ContainerInterface $container, $requestedName) {
                return new CustomersController(
                    $container->get(CustomerTable::class),
                    new CustomerInputFilter(),
                    new ClassMethods()
                );
            },
            OrdersController::class => function (ContainerInterface $container, $requestedName) {
                return new OrdersController(
                    $container->get(OrderTable::class),
                    $container->get(CustomerTable::class),
                    new OrderInputFilter(),
                    $container->get(OrderHydrator::class)
                );
            },
            InvoicesController::class => function (ContainerInterface $container, $requestedName) {
                return new InvoicesController(
                    $container->get(InvoiceTable::class),
                    $container->get(OrderTable::class),
                    new InvoicingService(
                        $container->get(OrderTable::class),
                        new InvoiceFactory()
                    )
                );
            }
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'validationErrors' => 'Application\View\Helper\ValidationErrors',
        ]
    ],
];
