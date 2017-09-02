<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\View;

use MyExpenses\Domain\Spender\SpenderId;

class SpenderView implements \MyExpenses\Infrastructure\ReadModel\SpenderView
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function spenderOfId(SpenderId $anSpenderId): array
    {
        $sql = <<<'SQL'
          SELECT * 
          FROM spenders
          WHERE spender_id = :spender_id
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['spender_id' => $anSpenderId->toString()]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}
