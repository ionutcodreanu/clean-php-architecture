<?php
namespace Application\Service\InputFilter;

use Zend\I18n\Validator\IsFloat;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class OrderInputFilter extends InputFilter
{
    public function __construct()
    {
        $customer = new InputFilter();
        $customerId = (new Input('id'))->setRequired(true);
        $customer->add($customerId);
        $this->add($customer, 'customer');

        $orderNumber = (new Input('orderNumber'))->setRequired(true);
        $orderNumber->getValidatorChain()->attach(new StringLength(['min' => 13, 'max' => 13]));
        $this->add($orderNumber);

        $orderTotal = (new Input('total'))->setRequired(true);
        $orderTotal->getValidatorChain()->attach(new IsFloat());
        $this->add($orderTotal);

        $description = (new Input('description'))->setRequired(true);
        $this->add($description);
    }
}
