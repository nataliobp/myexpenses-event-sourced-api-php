<?php

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $client;
    private $expenseListId;
    private $spenderId;
    private $categoryId;
    private $expenseListOverview;
    private $expenseId;
    private $categories;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost',
        ]);
    }

    /**
     * @Given there is a Spender registered with name :name
     */
    public function thereIsASpenderRegisteredWithName($name)
    {
        $response = $this->client->post('/spender', ['form_params' => ['name' => $name]]);
        $spender = $this->getFromResponseLocation($response);
        Assert::assertTrue(Uuid::isValid($spender['spender_id']));
        $this->spenderId[$name] = $spender['spender_id'];
    }

    /**
     * @Given there is a ExpenseList started with name :name
     */
    public function thereIsAExpenselistStartedWithName($name)
    {
        $response = $this->client->post('/expense_list', ['form_params' => ['name' => $name]]);
        $expenseList = $this->getFromResponseLocation($response);
        Assert::assertTrue(Uuid::isValid($expenseList['expense_list_id']));
        $this->expenseListId[$name] = $expenseList['expense_list_id'];
    }

    /**
     * @Given there is a Category created in the ExpenseList :expenseListName named :categoryName
     */
    public function thereIsACategoryCreatedInTheExpenselistNamed($expenseListName, $categoryName)
    {
        $response = $this->client->post("/expense_list/{$this->expenseListId[$expenseListName]}/category", ['form_params' => ['name' => $categoryName]]);
        $category = $this->getFromResponseLocation($response);
        Assert::assertTrue(Uuid::isValid($category['category_id']));
        $this->categoryId[$categoryName] = $category['category_id'];
    }

    /**
     * @When the Spender :spenderName adds an Expense of :amount with description :description and Category :categoryName to the ExpenseList :expenseListName
     */
    public function theSpenderAddAnExpenseOfWithDescriptionAndCategoryToTheExpenselist(string $spenderName, int $amount, string $description, string $categoryName, string $expenseListName)
    {
        $response = $this->client->post(
            "/expense_list/{$this->expenseListId[$expenseListName]}/expense",
            [
                'form_params' => [
                    'amount' => $amount,
                    'description' => $description,
                    'spender_id' => $this->spenderId[$spenderName],
                    'category_id' => $this->categoryId[$categoryName],
                ],
            ]
        );

        $expense = $this->getFromResponseLocation($response);
        Assert::assertTrue(Uuid::isValid($expense['expense_id']));
        $this->expenseId[$amount] = $expense['expense_id'];
    }

    /**
     * @Then there are :numCategories categories in the ExpenseList :expenseListName
     */
    public function thereAreCategoriesInTheExpenselist(int $numCategories, string $expenseListName)
    {
        $this->categories = json_decode(
            $this
                ->client
                ->get("/expense_list/{$this->expenseListId[$expenseListName]}/categories")
                ->getBody()
                ->getContents(),
            true
        );

        Assert::assertCount($numCategories, $this->categories);
    }

    /**
     * @Then there is a Category named :categoryName
     */
    public function thereIsACategoryNamed($categoryName)
    {
        $category = array_filter($this->categories, function (array $category) use ($categoryName) {
            return $category['name'] === $categoryName;
        });

        Assert::assertNotEmpty($category);
    }

    /**
     * @Then there are :numExpenses expenses in the ExpenseList :expenseListName
     */
    public function thereAreExpensesInTheExpenselist(int $numExpenses, string $expenseListName)
    {
        $this->expenseListOverview = json_decode(
            $this
                ->client
                ->get("/expense_list/{$this->expenseListId[$expenseListName]}/overview")
                ->getBody()
                ->getContents(),
            true
        );

        Assert::assertSame(
            $numExpenses,
            array_reduce($this->expenseListOverview['expensesBySpender'], function ($numExpenses, $expensesBySpender) {
                $numExpenses += count($expensesBySpender['expenses']);

                return $numExpenses;
            })
        );
    }

    /**
     * @Then there is a Expense of :amount in the ExpenseList named :expenseListName of Spender :spenderName with description :description and Category :categoryName
     */
    public function thereIsAExpenseOfInTheExpenselistNamedOfSpenderWithDescriptionAndCategory(int $amount, string $expenseListName, string $spenderName, string $description, string $categoryName)
    {
        $this->expenseListOverview = json_decode(
            $this
                ->client
                ->get("/expense_list/{$this->expenseListId[$expenseListName]}/overview")
                ->getBody()
                ->getContents(),
            true
        );

        $anExpense = array_filter(
            $this->expenseListOverview['expensesBySpender'][$this->spenderId[$spenderName]]['expenses'],
            function (array $anExpense) use ($amount, $description, $categoryName) {
                return $anExpense['amount'] === $amount
                    && $anExpense['description'] === $description
                    && $anExpense['category']['name'] === $categoryName
                    && $anExpense['category']['id'] === $this->categoryId[$categoryName];
            }
        );

        Assert::assertNotEmpty($anExpense);
    }

    /**
     * @Then the Spender :spenderName has spent :totalSpent in the ExpenseList :expenseListName
     */
    public function theSpenderHasSpent(string $spenderName, int $totalSpent, string $expenseListName)
    {
        $this->expenseListOverview = json_decode(
            $this
                ->client
                ->get("/expense_list/{$this->expenseListId[$expenseListName]}/overview")
                ->getBody()
                ->getContents(),
            true
        );

        Assert::assertSame(
            $totalSpent,
            $this->expenseListOverview['expensesBySpender'][$this->spenderId[$spenderName]]['totalSpent']
        );
    }

    /**
     * @Then the Spender :spenderName balance is :amount
     */
    public function theSpenderBalanceIs(string $spenderName, float $balance)
    {
        Assert::assertSame(
            $balance,
            (float) $this->expenseListOverview['expensesBySpender'][$this->spenderId[$spenderName]]['balance']
        );
    }

    /**
     * @When the Expense of :originalAmount is altered into :newAmount with description :description and Category :categoryName
     */
    public function theExpenseOfIsAlteredIntoWithDescriptionAndCategory(int $originalAmount, int $newAmount, string $description, string $categoryName)
    {
        $response = $this->client->put(
            "/expense/{$this->expenseId[$originalAmount]}",
            [
                'form_params' => [
                    'amount' => $newAmount,
                    'description' => $description,
                    'category_id' => $this->categoryId[$categoryName],
                ],
            ]
        );

        $expense = $this->getFromResponseLocation($response);
        Assert::assertTrue(Uuid::isValid($expense['expense_id']));
        $this->expenseId[$newAmount] = $expense['expense_id'];
    }

    /**
     * @When the Expense of :amount is removed from the ExpenseList :expenseListName
     */
    public function theExpenseOfIsRemoved($amount, $expenseListName)
    {
        $this->client->delete(
            "/expense_list/{$this->expenseListId[$expenseListName]}/expense/{$this->expenseId[$amount]}"
        );
    }

    private function getFromResponseLocation($response): array
    {
        return json_decode(
            $this
                ->client
                ->get(current($response->getHeader('Location')))
                ->getBody()
                ->getContents(),
            true
        );
    }
}
