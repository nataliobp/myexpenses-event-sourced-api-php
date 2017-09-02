<?php

namespace MyExpenses\Infrastructure\DeliveryMechanism\HTTP;

use MyExpenses\Application\Command\AddAnExpense\AddAnExpenseCommand;
use MyExpenses\Application\Command\AlterAnExpense\AlterAnExpenseCommand;
use MyExpenses\Application\Command\CreateACategory\CreateACategoryCommand;
use MyExpenses\Application\Command\RegisterASpender\RegisterASpenderCommand;
use MyExpenses\Application\Command\RemoveAnExpense\RemoveAnExpenseCommand;
use MyExpenses\Application\Command\StartAnExpenseList\StartAnExpenseListCommand;
use MyExpenses\Application\Query\GetAnExpense\GetAnExpenseCommand;
use MyExpenses\Application\Query\GetAnExpenseList\GetAnExpenseListCommand;
use MyExpenses\Application\Query\GetAnExpenseListOverview\GetAnExpenseListOverviewCommand;
use MyExpenses\Application\Query\GetACategory\GetACategoryCommand;
use MyExpenses\Application\Query\GetASpender\GetASpenderCommand;
use MyExpenses\Application\Query\GetCategoriesOfAnExpenseList\GetCategoriesOfAnExpenseListCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends RestController
{
    public function startAnExpenseList(Request $request)
    {
        try {
            $anListExpenseId = $this->commandBus()->handle(
                new StartAnExpenseListCommand(
                    $request->request->get('name')
                )
            );

            $response = new Response('ok', 201);
            $response->headers->set('Location', "/expense_list/{$anListExpenseId}");

            return $response;
        } catch (\Throwable $t) {
            return new Response($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function registerASpender(Request $request)
    {
        try {
            $anSpenderId = $this->commandBus()->handle(
                new RegisterASpenderCommand(
                    $request->request->get('name')
                )
            );

            $response = new Response('ok', 201);
            $response->headers->set('Location', "/spender/{$anSpenderId}");

            return $response;
        } catch (\Throwable $t) {
            return new Response($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createACategory(string $expenseListId, Request $request)
    {
        try {
            $aCategoryId = $this->commandBus()->handle(
                new CreateACategoryCommand(
                    $request->request->get('name'),
                    $expenseListId
                )
            );

            $response = new Response('ok', 201);
            $response->headers->set('Location', "/category/{$aCategoryId}");

            return $response;
        } catch (\Throwable $t) {
            return new Response($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addAnExpense(string $expenseListId, Request $request)
    {
        try {
            $anExpenseId = $this->commandBus()->handle(
                new AddAnExpenseCommand(
                    $request->request->get('amount'),
                    $request->request->get('description'),
                    $request->request->get('category_id'),
                    $request->request->get('spender_id'),
                    $expenseListId
                )
            );

            $response = new Response('ok', 201);
            $response->headers->set('Location', "/expense/{$anExpenseId}");

            return $response;
        } catch (\Throwable $t) {
            return new Response($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function alterAnExpense(string $expenseId, Request $request)
    {
        try {
            $anExpenseId = $this->commandBus()->handle(
                new AlterAnExpenseCommand(
                    $expenseId,
                    $request->request->get('amount'),
                    $request->request->get('description'),
                    $request->request->get('category_id')
                )
            );

            $response = new Response('', 204);
            $response->headers->set('Location', "/expense/{$anExpenseId}");

            return $response;
        } catch (\Throwable $t) {
            return new Response($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeAnExpense(string $expenseListId, string $expenseId)
    {
        try {
            $anExpenseId = $this->commandBus()->handle(
                new RemoveAnExpenseCommand(
                    $expenseListId,
                    $expenseId
                )
            );

            $response = new Response('', 204);
            $response->headers->set('Location', "/expense/{$anExpenseId}");

            return $response;
        } catch (\Throwable $t) {
            return new Response($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAnExpense(string $expenseId)
    {
        try {
            return new JsonResponse(
                $this->commandBus()->handle(
                    new GetAnExpenseCommand($expenseId)
                )
            );
        } catch (\Throwable $t) {
            return new JsonResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAnExpenseListOverview(string $expenseListId)
    {
        try {
            return new JsonResponse(
                $this->commandBus()->handle(
                    new GetAnExpenseListOverviewCommand($expenseListId)
                )
            );
        } catch (\Throwable $t) {
            return new JsonResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAnExpenseList(string $expenseListId)
    {
        try {
            return new JsonResponse(
                $this->commandBus()->handle(
                    new GetAnExpenseListCommand($expenseListId)
                )
            );
        } catch (\Throwable $t) {
            return new JsonResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getASpender(string $spenderId)
    {
        try {
            return new JsonResponse(
                $this->commandBus()->handle(
                    new GetASpenderCommand($spenderId)
                )
            );
        } catch (\Throwable $t) {
            return new JsonResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getACategory(string $categoryId)
    {
        try {
            return new JsonResponse(
                $this->commandBus()->handle(
                    new GetACategoryCommand($categoryId)
                )
            );
        } catch (\Throwable $t) {
            return new JsonResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCategoriesOfExpenseListOfId(string $expenseListId)
    {
        try {
            return new JsonResponse(
                $this->commandBus()->handle(
                    new GetCategoriesOfAnExpenseListCommand($expenseListId)
                )
            );
        } catch (\Throwable $t) {
            return new JsonResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
