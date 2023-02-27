<?php

namespace App\Application\DTO;

use Symfony\Component\HttpFoundation\Request;

readonly class QueryParamsDTO
{

    public function __construct(
        public ?string $filters = null,
        public ?string $sort = null,
        public ?int $page = null,
        public ?int $limit = null,
    ) {
    }

    public static function fromArray(array $request): self
    {
        return new self(
            filters: $request['filters'] ?? null,
            sort: $request['sort'] ?? null,
            page: $request['page'] ?? null,
            limit: $request['limit'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            filters: $request->query->get('filters'),
            sort: $request->query->get('sort'),
            page: $request->query->get('page'),
            limit: $request->query->get('limit'),
        );
    }

    public function addFilters(string $filters): self
    {
        return new self(
            filters: $this->filters ? $this->filters . ',' . $filters : $filters,
            sort: $this->sort,
            page: $this->page,
            limit: $this->limit,
        );
    }
}
