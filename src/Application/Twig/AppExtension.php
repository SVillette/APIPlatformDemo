<?php

declare(strict_types=1);

namespace App\Application\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AppExtension extends AbstractExtension
{
    /**
     * @return array<int, TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination_item_url', [PaginationRuntime::class, 'getUrlForPage']),
        ];
    }
}
