<?php

namespace Application\Controller;

use Application\Persistence\Hydrator\OrderHydrator;
use Application\Service\InputFilter\OrderInputFilter;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OrdersController extends AbstractActionController
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var OrderInputFilter
     */
    private $orderInputFilter;
    /**
     * @var OrderHydrator
     */
    private $orderHydrator;

    /**
     * Orders constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderInputFilter $orderInputFilter
     * @param OrderHydrator $orderHydrator
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        OrderInputFilter $orderInputFilter,
        OrderHydrator $orderHydrator

    ) {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->orderInputFilter = $orderInputFilter;
        $this->orderHydrator = $orderHydrator;
    }

    public function indexAction()
    {
        return [
            'orders' => $this->orderRepository->getAll()
        ];
    }

    public function viewAction()
    {
        $orderId = $this->params()->fromRoute('id');
        $order = $this->orderRepository->getById($orderId);

        return [
            'order' => $order,
        ];
    }

    public function newAction()
    {
        $viewModel = new ViewModel();
        $order = new Order();

        if ($this->getRequest()->isPost()) {
            $this->orderInputFilter->setData($this->params()->fromPost());
            if ($this->orderInputFilter->isValid()) {
                $order = $this->orderHydrator->hydrate($this->orderInputFilter->getValues(), $order);
                $this->orderRepository->persist($order);
                $this->flashMessenger()->addSuccessMessage('Order created');
                $this->redirect()->toUrl('/orders/view/' . $order->getId());
            } else {
                $this->orderHydrator->hydrate($this->orderInputFilter->getValues(), $order);
                $viewModel->setVariable('errors', $this->orderInputFilter->getMessages());
            }
        }

        $viewModel->setVariable('customers', $this->customerRepository->getAll());
        $viewModel->setVariable('order', $order);
        return $viewModel;
    }
}
