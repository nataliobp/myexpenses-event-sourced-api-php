<?php

namespace MyExpenses\Infrastructure\ReadModel\InMemory\Projection;

use EventSourcing\Projection\InMemoryProjector;
use MyExpenses\Domain\Category\CategoryWasCreated;

class CategoryProjector extends InMemoryProjector
{
    const CATEGORY_VIEW = 'category_view';

    protected function whenCategoryWasCreated(CategoryWasCreated $anEvent): void
    {
        $this->views(self::CATEGORY_VIEW)->append([
            'category_id' => $anEvent->categoryId(),
            'name' => $anEvent->name(),
            'occurred_on' => $anEvent->occurredOn()->getTimestamp(),
        ]);
    }
}
