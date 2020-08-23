<?php

namespace App\Domain\Repository;

use PDO;

/**
 * Repository.
 */
class AlertRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Insert alert row.
     *
     * @param array $alerts
     * @return int The new ID
     */
    public function insertAlerts(array $alerts): int
    {
        $placeholder = str_repeat('(?,?),', count($alerts) - 1) . '(?,?)';
        $sql = "INSERT INTO alerts (user_id, type) VALUES $placeholder;";

        $flattened = [];
        foreach ($alerts as $alert) {
            $flattened[] = $alert['user_id'];
            $flattened[] = $alert['type'];
        }

        $this->connection->prepare($sql)->execute($flattened);

        return (int)$this->connection->lastInsertId();
    }

    public function setSent(string $type, int $userId)
    {
        $sql = "Update alerts as oa
                SET oa.sent = 1, oa.delivered_at = NOW() 
                WHERE oa.id = (SELECT ia.id
                               FROM (select * from alerts) as ia
                               WHERE ia.user_id = ? AND ia.type = ? AND ia.sent = 0
                               ORDER BY ia.id DESC
                               LIMIT 1)";

        $this->connection->prepare($sql)->execute([$userId, $type]);
    }
}