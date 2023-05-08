<?php

declare(strict_types=1);

namespace App\Api\Paginator;

use ApiPlatform\State\Pagination\PaginatorInterface;
use ArrayIterator;
use Iterator;
use IteratorAggregate;
use Traversable;

use function ceil;
use function count;

/**
 * @template T of object
 *
 * @implements PaginatorInterface<T>
 * @implements IteratorAggregate<mixed, T>
 */
final class DoctrineDTOPaginator implements PaginatorInterface, IteratorAggregate
{
    private Iterator $iterator;

    /**
     * @param array<int, T> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly int $currentPage,
        private readonly int $totalItems,
        private readonly int $itemsPerPage,
    ) {
        $this->iterator = new ArrayIterator($items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getLastPage(): float
    {
        return (int) ceil($this->totalItems / $this->itemsPerPage);
    }

    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    public function getIterator(): Traversable
    {
        return $this->iterator;
    }
}
