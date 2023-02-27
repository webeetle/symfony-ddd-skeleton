<?php

namespace App\Infrastructure\Repository;

use App\Core\Interface\Pageable;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorAdapter extends Paginator implements Pageable
{
    public function total(): int
    {
        return $this->count();
    }

    public function getSlice(int $offset, int $length): iterable
    {
        $this->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($length);

        return $this->getIterator();
    }

}
