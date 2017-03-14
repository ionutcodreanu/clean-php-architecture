<?php
namespace Application\Persistence\Hydrator\Strategy;

use Zend\Hydrator\Strategy\DefaultStrategy;

class DateStrategy extends DefaultStrategy
{
    public function hydrate($value)
    {
        if (is_string($value)) {
            $value = new \DateTime($value);
        }
        return $value;
    }

    public function extract($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d');
        }
        return $value;
    }
}
