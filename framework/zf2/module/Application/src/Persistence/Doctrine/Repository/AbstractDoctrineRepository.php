<?php
namespace Application\Persistence\Doctrine\Repository;

use CleanPhp\Invoicer\Domain\Entity\AbstractEntity;
use CleanPhp\Invoicer\Domain\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManager;

class AbstractDoctrineRepository implements RepositoryInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var
     */
    protected $entityClass;

    /**
     * AbstractDoctrineRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        if (empty($this->entityClass)) {
            throw new \RuntimeException(
                __CLASS__ . '::$entityClass is not defined'
            );
        }
        $this->entityManager = $entityManager;
    }

    public function getById($entityId)
    {
        return $this->entityManager->find($this->entityClass, $entityId);
    }

    public function persist(AbstractEntity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
        return $this;
    }

    public function delete(AbstractEntity $entity)
    {
        $this->entityManager->detach($entity);
        $this->entityManager->flush();
    }

    public function getAll()
    {
        return $this->entityManager->getRepository($this->entityClass)->findAll();
    }
}
