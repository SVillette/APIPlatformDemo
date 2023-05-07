<?php

declare(strict_types=1);

namespace App\Application\Paginator;

/**
 * @template T
 */
interface PaginatorInterface
{
    /**
     * @return array<int, T>
     */
    public function getItems(): array;

    public function totalItems(): int;

    public function getCurrentPage(): int;

    public function hasPreviousPage(): bool;

    public function getPreviousPage(): int;

    public function hasNextPage(): bool;

    public function getNextPage(): int;

    public function hasPage(int $page): bool;

    public function getPagesTotal(): int;

    public function canPaginate(): bool;
}
