<?php

namespace CleanPhp\Invoicer\Domain\Repository;

interface RepositoryInterface
{
    public function getById($entityId);

    public function persist($entity);

    public function delete($entity);

    public function getAll();
}
