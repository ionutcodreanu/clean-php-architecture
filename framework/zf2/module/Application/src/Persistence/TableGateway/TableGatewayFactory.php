<?php
namespace Application\Persistence\TableGateway;

use CleanPhp\Invoicer\Domain\Entity\AbstractEntity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\HydratorInterface;

class TableGatewayFactory
{
    public function createGateway(Adapter $dbAdapter, HydratorInterface $hydrator, AbstractEntity $object, $table)
    {
        $resultSet = new HydratingResultSet($hydrator, $object);
        return new TableGateway($table, $dbAdapter, null, $resultSet);
    }
}
