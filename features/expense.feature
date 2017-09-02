Feature: Add expenses to an expense list

  Scenario: Adding expenses
    Given there is a Spender registered with name "theSpender"
    And there is a Spender registered with name "anotherSpender"
    And there is a ExpenseList started with name "Piset"
    And there is a Category created in the ExpenseList "Piset" named "Groceries"
    And there is a Category created in the ExpenseList "Piset" named "Restaurants"
    When the Spender "theSpender" adds an Expense of 2550 with description "vegetables" and Category "Groceries" to the ExpenseList "Piset"
    And the Spender "anotherSpender" adds an Expense of 5489 with description "fish" and Category "Groceries" to the ExpenseList "Piset"
    And the Spender "anotherSpender" adds an Expense of 11710 with description "burger" and Category "Restaurants" to the ExpenseList "Piset"
    Then there are 2 categories in the ExpenseList "Piset"
    And there is a Category named "Groceries"
    And there is a Category named "Restaurants"
    And there are 3 expenses in the ExpenseList "Piset"
    And there is a Expense of 2550 in the ExpenseList named "Piset" of Spender "theSpender" with description "vegetables" and Category "Groceries"
    And there is a Expense of 11710 in the ExpenseList named "Piset" of Spender "anotherSpender" with description "burger" and Category "Restaurants"
    And the Spender "theSpender" has spent 2550 in the ExpenseList "Piset"
    And the Spender "anotherSpender" has spent 17199 in the ExpenseList "Piset"
    And the Spender "theSpender" balance is -7324.5
    And the Spender "anotherSpender" balance is 7324.5
    When the Expense of 5489 is altered into 678 with description "fish and chips" and Category "Groceries"
    Then the Spender "anotherSpender" has spent 12388 in the ExpenseList "Piset"
    And the Spender "theSpender" balance is -4919
    And the Spender "anotherSpender" balance is 4919
    When the Expense of 11710 is removed from the ExpenseList "Piset"
    Then the Spender "anotherSpender" has spent 678 in the ExpenseList "Piset"
    And the Spender "theSpender" balance is 936.0
    And the Spender "anotherSpender" balance is -936.0