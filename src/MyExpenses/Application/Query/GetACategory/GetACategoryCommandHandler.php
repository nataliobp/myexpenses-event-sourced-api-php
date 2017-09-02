<?php

namespace MyExpenses\Application\Query\GetACategory;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Infrastructure\ReadModel\CategoryView;

class GetACategoryCommandHandler implements CommandHandler
{
    private $categoryView;

    public function __construct(CategoryView $categoryView)
    {
        $this->categoryView = $categoryView;
    }

    /**
     * @param GetACategoryCommand|Command $command
     *
     * @return array
     */
    public function handle(Command $command)
    {
        return $this->categoryView->categoryOfId(CategoryId::ofId($command->categoryId()));
    }
}
