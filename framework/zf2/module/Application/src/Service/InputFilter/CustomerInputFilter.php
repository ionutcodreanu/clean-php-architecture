<?php
namespace Application\Service\InputFilter;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;

class CustomerInputFilter extends InputFilter
{
    public function __construct()
    {
        $name = (new Input('name'))
            ->setRequired(true);
        $this->add($name);

        $email = (new Input('email'))
            ->setRequired(true);
        $email->getValidatorChain()
            ->attach(new EmailAddress());

        $this->add($email);
    }

}
