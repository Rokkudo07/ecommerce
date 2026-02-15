<?php

namespace App\Service;

use App\Entity\NavigationItem;
use App\Repository\NavigationItemRepository;

class NavigationBuilder
{
    public function __construct(
        private NavigationItemRepository $repository
    ) {
    }

    /**
     * Build navigation tree from visible items
     * 
     * @return NavigationItem[]
     */
    public function buildTree(): array
    {
        $items = $this->repository->findVisibleOrdered();
        
        // Load children for each root item
        foreach ($items as $item) {
            $this->loadChildren($item);
        }
        
        return $items;
    }

    /**
     * Load children recursively for an item
     * Children are already loaded and ordered by Doctrine (OrderBy annotation)
     */
    private function loadChildren(NavigationItem $item): void
    {
        // Children are already loaded and ordered by Doctrine
        // This method is kept for potential future logic
    }

    /**
     * Get URL for a navigation item
     */
    public function getUrl(NavigationItem $item): ?string
    {
        if ($item->getType() === 'dropdown_only') {
            return null;
        }

        return $item->getUrl();
    }
}
