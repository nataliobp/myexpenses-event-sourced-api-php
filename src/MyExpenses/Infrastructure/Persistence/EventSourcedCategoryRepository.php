<?php

namespace MyExpenses\Infrastructure\Persistence;

use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\Category\CategoryRepository;
use EventSourcing\Repository\EventSourcedRepository;

class EventSourcedCategoryRepository extends EventSourcedRepository implements CategoryRepository
{
    public function addACategory(Category $aCategory): void
    {
        $this->appendAndPublishRecordedEvents($aCategory);
    }

    public function categoryOfId(CategoryId $aCategoryId): Category
    {
        $aCategory = new Category($aCategoryId);

        return $this->eventStore->reconstituteAggregate($aCategory);
    }
}
