<?php

namespace App\Twig;

use App\Service\NavigationBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    public function __construct(
        private NavigationBuilder $navigationBuilder
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_navigation', [$this, 'getNavigation']),
            new TwigFunction('get_navigation_builder', [$this, 'getNavigationBuilder']),
        ];
    }

    public function getNavigation(): array
    {
        return $this->navigationBuilder->buildTree();
    }

    public function getNavigationBuilder(): NavigationBuilder
    {
        return $this->navigationBuilder;
    }
}
