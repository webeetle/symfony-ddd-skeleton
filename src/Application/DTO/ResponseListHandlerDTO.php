<?php

namespace App\Application\DTO;

use App\Core\Interface\Pageable;

class ResponseListHandlerDTO
{
    public function __construct(
        public iterable $data = [],
        public int $total = 0,
        public int $page = 0,
        public int $limit = 0,
        public int $filtered = 0
    ) {
    }

    public static function fromPageable(int $total, int $page, int $limit, Pageable $pageable): self
    {
        $offset = $page ?? 0;
        $length = $limit ?? 0;
        if (empty($offset) || empty($length)) {
            $offset = 0;
            $length = $total;
        }

        return new self(
            data: $pageable->getSlice($offset * $length, $length),
            total: $total,
            page: $page,
            limit: $limit,
            filtered: $pageable->total()
        );
    }
}
