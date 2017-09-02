<?php

namespace MyExpenses\Domain\Category;

interface CategoryRepository
{
    public function categoryOfId(CategoryId $aCategoryId): Category;
    public function addACategory(Category $aCategory): void;
}
