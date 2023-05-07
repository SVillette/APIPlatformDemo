<?php

declare(strict_types=1);

namespace App\Application\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RuntimeException;

use function ceil;

/**
 * @template T
 *
 * @implements PaginatorInterface<T>
 */
final class DoctrineORMPaginator implements PaginatorInterface
{
    /** @var array<int, T> */
    private array $items;

    private int $itemsTotal;

    private int $itemsPerPage;

    public function __construct(private readonly Query $query, private readonly int $currentPage = 1)
    {
        $paginator = new Paginator($query);
        $this->itemsTotal = $paginator->count();
        $this->itemsPerPage = $this->query->getMaxResults() ?? $paginator->count();
    }

    public function getItems(): array
    {
        if (isset($this->items)) {
            return $this->items;
        }

        $this->items = $this->query->getResult();

        return $this->items;
    }

    public function totalItems(): int
    {
        return $this->itemsTotal;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function hasPreviousPage(): bool
    {
        return 1 < $this->currentPage;
    }

    public function getPreviousPage(): int
    {
        if (!$this->hasPreviousPage()) {
            throw new RuntimeException('Cannot get previous page');
        }

        return $this->currentPage - 1;
    }

    public function hasNextPage(): bool
    {
        return $this->itemsTotal > ($this->currentPage * $this->itemsPerPage);
    }

    public function getNextPage(): int
    {
        if (!$this->hasNextPage()) {
            throw new RuntimeException('Cannot get previous page');
        }

        return $this->currentPage + 1;
    }

    public function hasPage(int $page): bool
    {
        return $this->itemsTotal > ($page * ($this->itemsPerPage - 1));
    }

    public function getPagesTotal(): int
    {
        return (int) ceil($this->itemsTotal / $this->itemsPerPage);
    }

    public function canPaginate(): bool
    {
        return $this->itemsTotal > $this->itemsPerPage;
    }
}
