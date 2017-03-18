<?php

namespace CleanPhp\Invoicer\Domain\Repository;

use CleanPhp\Invoicer\Domain\Entity\AbstractEntity;

interface RepositoryInterface
{
    public function getById($entityId);

    /**
     * @param AbstractEntity $entity
     * @return $this
     */
    public function persist(AbstractEntity $entity);

    public function delete(AbstractEntity $entity);

    public function getAll();
}
