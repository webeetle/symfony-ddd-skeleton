<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\DTO\QueryParamsDTO;
use App\Application\DTO\ResponseListHandlerDTO;
use App\Core\Entity\Entity;
use App\Infrastructure\Repository\RepositoryUtils;

trait RepositoryTrait
{

    public function search(?QueryParamsDTO $params = null): ResponseListHandlerDTO
    {
        $total = $this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $name_class = explode("\\", $this->getEntityClassName());
        $alias_table = strtolower(end($name_class));
        $queryBuilder = $this->createQueryBuilder($alias_table);
        if ($params) {
            $queryBuilder = RepositoryUtils::applyFilters($queryBuilder, $params, $alias_table);
            $queryBuilder = RepositoryUtils::applyOrder($queryBuilder, $params, $alias_table);
        }

        $paginator = new PaginatorAdapter($queryBuilder);

        $page = $params->page ?? 0;
        $limit = $params->limit ?? 0;
        return ResponseListHandlerDTO::fromPageable(
            total: $total,
            page: $page,
            limit: $limit,
            pageable: $paginator
        );
    }

    public function findById(string $entityId): ?Entity
    {
        return $this->find($entityId);
    }

    public function add(Entity $entity, bool $flush = false): string
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->flush();
        }

        return $entity->getId();
    }

    public function delete(Entity $entity, bool $flush = false) : void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->flush();
        }
    }

    public function getEntityClassName(): string
    {
        return $this->getEntityName();
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
