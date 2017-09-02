<?php

namespace MyExpenses\Application\Query\GetACategory;

use MyExpenses\Application\Command;

class GetACategoryCommand implements Command
{
    private $categoryId;

    public function __construct($aCategoryId)
    {
        $this->categoryId = $aCategoryId;
    }

    public function categoryId()
    {
        return $this->categoryId;
    }
}
