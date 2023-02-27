<?php

namespace App\Core\Interface;

use App\Application\DTO\QueryParamsDTO;
use App\Application\DTO\ResponseListHandlerDTO;
use App\Core\Entity\Entity;

interface EntityRepositoryInterface
{

    public function search(?QueryParamsDTO $params = null): ResponseListHandlerDTO;

    public function findById(string $entityId): ?Entity;

    public function add(Entity $entity, bool $flush = false): string;

    public function remove(Entity $entity, bool $flush = false): void;

    public function delete(Entity $entity, bool $flush = false): void;

    public function getEntityClassName(): string;

    public function flush(): void;

}
