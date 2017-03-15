<?php
namespace Application\Persistence\DataTable;

use CleanPhp\Invoicer\Domain\Entity\AbstractEntity;
use CleanPhp\Invoicer\Domain\Repository\RepositoryInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\HydratorInterface;
use Zend\ModuleManager\Feature\HydratorProviderInterface;

class AbstractDataTable implements RepositoryInterface
{
    /**
     * @var TableGateway
     */
    protected $gateway;
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * AbstractDataTable constructor.
     * @param TableGateway $gateway
     * @param HydratorInterface $hydrator
     */
    public function __construct(TableGateway $gateway, HydratorInterface $hydrator)
    {
        $this->gateway = $gateway;
        $this->hydrator = $hydrator;
    }

    /**
     * @todo use null object instead of false
     *
     * @param $entityId
     * @return AbstractEntity|bool
     */
    public function getById($entityId)
    {
        $result = $this->gateway
            ->select(['id' => intval($entityId)])
            ->current();

        return $result ? $result : false;
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getAll()
    {
        return $this->gateway->select();
    }

    public function persist(AbstractEntity $entity)
    {
        $data = $this->hydrator->extract($entity);

        if ($this->hasIdentity($entity)) {
            $this->gateway->update($data, ['id' => $entity->getId()]);
        } else {
            $this->gateway->insert($data);
            $entity->setId($this->gateway->getLastInsertValue());
        }

        return $this;
    }

    public function delete(AbstractEntity $entity)
    {
        $this->gateway->delete(['id' => $entity->getId()]);
    }

    private function hasIdentity(AbstractEntity $entity)
    {
        return !empty($entity->getId());
    }

}
