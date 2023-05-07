<?php

declare(strict_types=1);

namespace App\Twig;

use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;
use Webmozart\Assert\Assert;

final class PaginationRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function getUrlForPage(int $page = 1): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new RuntimeException('Could not get the current request');
        }

        $routeName =
            $request->attributes->get('_route') ??
            throw new RuntimeException('Could not get "_route" parameter from request')
        ;

        Assert::string($routeName);

        $routeParams =
            $request->attributes->get('_route_params') ??
            throw new RuntimeException('Could not get "_route_params" parameter from request')
        ;

        Assert::isArray($routeParams);

        $routeParams['page'] = $page;

        return $this->urlGenerator->generate($routeName, $routeParams);
    }
}
