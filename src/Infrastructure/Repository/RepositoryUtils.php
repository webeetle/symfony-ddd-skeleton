<?php

namespace App\Infrastructure\Repository;

use App\Application\DTO\QueryParamsDTO;
use Doctrine\ORM\QueryBuilder;

class RepositoryUtils
{
    public static function applyFilters(QueryBuilder $queryBuilder, QueryParamsDTO $queryParams, string $alias_table = 'e'): QueryBuilder
    {
        $paramFilters = $queryParams->filters;
        if (empty($paramFilters)) {
            return $queryBuilder;
        }
        $filters = explode(',', $paramFilters);
        foreach ($filters as $filter) {
            [$field, $operator, $value] = explode(':', $filter);
            $field = $alias_table.'.'.$field;
            if ($value === 'null') {
                $value = null;
            }
            if ($value === 'true') {
                $value = true;
            }
            if ($value === 'false') {
                $value = false;
            }
            if (is_numeric($value) && !static::isUUID($value)) {
                $value = (float)$value;
            }

            switch ($operator) {
                case 'eq':
                    $valueSplit = explode('|', $value);
                    if (count($valueSplit) > 1) {
                        $queryBuilder->andWhere($queryBuilder->expr()->in($field, $valueSplit));
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->eq($field, $queryBuilder->expr()->literal($value)));
                    }
                    break;
                case 'neq':
                    $queryBuilder->andWhere($queryBuilder->expr()->neq($field, $queryBuilder->expr()->literal($value)));
                    break;
                case 'lt':
                    $queryBuilder->andWhere($queryBuilder->expr()->lt($field, $queryBuilder->expr()->literal($value)));
                    break;
                case 'lte':
                    $queryBuilder->andWhere($queryBuilder->expr()->lte($field, $queryBuilder->expr()->literal($value)));
                    break;
                case 'gt':
                    $queryBuilder->andWhere($queryBuilder->expr()->gt($field, $queryBuilder->expr()->literal($value)));
                    break;
                case 'gte':
                    $queryBuilder->andWhere($queryBuilder->expr()->gte($field, $queryBuilder->expr()->literal($value)));
                    break;
                case 'like':
                    $valueSplit = explode('|', $value);
                    if (count($valueSplit) > 1) {
                        $expressionOr = $queryBuilder->expr()->orX();
                        foreach ($valueSplit as $value) {
                            $expressionOr->add($queryBuilder->expr()->like($field, $queryBuilder->expr()->literal('%' . $value . '%')));
                        }
                        if ($expressionOr->count() > 0) {
                            $queryBuilder->andWhere($expressionOr);
                        }
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->like($field, $queryBuilder->expr()->literal('%' . $value . '%')));
                    }
                    break;
                case 'elike':
                    $valueSplit = explode('|', $value);
                    if (count($valueSplit) > 1) {
                        $expressionOr = $queryBuilder->expr()->orX();
                        foreach ($valueSplit as $value) {
                            $expressionOr->add($queryBuilder->expr()->like($field, $queryBuilder->expr()->literal($value . '%')));
                        }
                        if ($expressionOr->count() > 0) {
                            $queryBuilder->andWhere($expressionOr);
                        }
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->like($field, $queryBuilder->expr()->literal($value . '%')));
                    }
                    break;
                case 'slike':
                    $valueSplit = explode('|', $value);
                    if (count($valueSplit) > 1) {
                        $expressionOr = $queryBuilder->expr()->orX();
                        foreach ($valueSplit as $value) {
                            $expressionOr->add($queryBuilder->expr()->like($field, $queryBuilder->expr()->literal('%' . $value)));
                        }
                        if ($expressionOr->count() > 0) {
                            $queryBuilder->andWhere($expressionOr);
                        }
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->like($field, $queryBuilder->expr()->literal('%' . $value)));
                    }
                    break;
                case 'in':
                    $valueSplit = explode('|', $value);
                    if (count($valueSplit) > 1) {
                        $queryBuilder->andWhere($queryBuilder->expr()->in($field, $valueSplit));
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->in($field, $valueSplit));
                    }
                    break;
                case 'nin':
                    $valueSplit = explode('|', $value);
                    if (count($valueSplit) > 1) {
                        $queryBuilder->andWhere($queryBuilder->expr()->notIn($field, $valueSplit));
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->notIn($field, $valueSplit));
                    }
                    break;
                case 'isnull':
                    $queryBuilder->andWhere($queryBuilder->expr()->isNull($field));
                    break;
                case 'isnotnull':
                    $queryBuilder->andWhere($queryBuilder->expr()->isNotNull($field));
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid operator');
            }
        }

        return $queryBuilder;
    }

    public static function applyOrder(QueryBuilder $queryBuilder, QueryParamsDTO $queryParams, string $alias_table = 'e'): QueryBuilder
    {
        $paramSort = $queryParams->sort;
        if (empty($paramSort)) {
            return $queryBuilder;
        }
        $sort = explode(',', $paramSort);
        foreach ($sort as $sortItem) {
            [$field, $direction] = explode(':', $sortItem);
            $field = $alias_table.'.'.$field;
            $queryBuilder->addOrderBy($field, $direction);
        }

        return $queryBuilder;
    }

    public static function applyPagination(QueryBuilder $queryBuilder, QueryParamsDTO $queryParams): QueryBuilder
    {
        $paramPage = $queryParams->page ?? 0;
        $paramLimit = $queryParams->limit ?? 0;
        if (empty($paramPage) || empty($paramLimit)) {
            return $queryBuilder;
        }
        $queryBuilder->setFirstResult(($paramPage - 1) * $paramLimit);
        $queryBuilder->setMaxResults($paramLimit);

        return $queryBuilder;
    }

    public static function isUUID(string $id): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id);
    }

}
