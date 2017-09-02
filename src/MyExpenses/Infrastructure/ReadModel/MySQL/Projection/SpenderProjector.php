<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\Projection;

use EventSourcing\Projection\MySQLProjector;
use MyExpenses\Domain\Spender\SpenderWasRegistered;

class SpenderProjector extends MySQLProjector
{
    protected function whenSpenderWasRegistered(SpenderWasRegistered $anEvent): void
    {
        $sql = <<<'SQL'
          INSERT INTO spenders (spender_id, name)
          VALUES (:spender_id, :name);
SQL;
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute([
            'spender_id' => $anEvent->spenderId(),
            'name' => $anEvent->name(),
        ]);
    }
}
