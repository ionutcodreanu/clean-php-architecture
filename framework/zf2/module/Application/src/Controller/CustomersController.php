<?php

namespace Application\Controller;

use Application\Service\InputFilter\CustomerInputFilter;
use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CustomersController extends AbstractActionController
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var CustomerInputFilter
     */
    private $inputFilter;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * Customers constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInputFilter $inputFilter
     * @param HydratorInterface $hydrator
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerInputFilter $inputFilter,
        HydratorInterface $hydrator
    ) {
        $this->customerRepository = $customerRepository;
        $this->inputFilter = $inputFilter;
        $this->hydrator = $hydrator;
    }

    public function indexAction()
    {
        return [
            'customers' => $this->customerRepository->getAll()
        ];
    }

    public function newAction()
    {
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $customer = new Customer();
        if ($request->isPost()) {
            $this->inputFilter->setData($this->params()->fromPost());
            if ($this->inputFilter->isValid()) {
                $customer = $this->hydrator->hydrate(
                    $this->inputFilter->getValues(),
                    $customer
                );
                $this->customerRepository->persist($customer);
                $this->flashMessenger()->addSuccessMessage('Customer saved');
                $this->redirect()->toUrl('/customers');
            } else {
                $this->hydrator->hydrate(
                    $this->params()->fromPost(),
                    $customer
                );
                $viewModel->setVariable('errors', $this->inputFilter->getMessages());
            }
        }
        $viewModel->setVariable('customer', $customer);
        return $viewModel;
    }
}
