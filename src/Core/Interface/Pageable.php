<?php

namespace App\Core\Interface;

interface Pageable
{
    public function total(): int;

    public function getSlice(int $offset, int $length): iterable;

}
