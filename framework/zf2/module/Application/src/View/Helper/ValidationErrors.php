<?php
namespace Application\View\Helper;

use Vnn\Keyper\Keyper;
use Zend\View\Helper\AbstractHelper;

class ValidationErrors extends AbstractHelper
{
    /**
     * @param $element
     * @return string
     */
    public function __invoke($element)
    {
        if ($errors = $this->getErrors($element)) {
            return '<div class="alert alert-danger">' .
                implode('. ', $errors) .
                '</div>';
        }
        return '';
    }

    protected function getErrors($element)
    {
        if (!isset($this->getView()->errors)) {
            return false;
        }
        $errors = Keyper::create($this->getView()->errors);
        return $errors->get($element) ?: false;
    }
}
