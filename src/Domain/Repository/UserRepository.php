<?php

namespace App\Domain\Repository;

use PDO;

/**
 * Repository.
 */
class UserRepository
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

    public function getThresholds(int $id): array
    {
        $sql = "SELECT *
                FROM user_thresholds 
                WHERE id = ?;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Insert alert row.
     *
     * @param string $email
     * @return int The new ID
     */
    public function countUserByEmail(string $email): int
    {
        $sql = "SELECT COUNT(id) as count
                FROM users 
                WHERE email = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    /**
     * @param $email
     * @return int
     */
    public function getUserIdByEmail($email): int
    {
        $sql = "SELECT id
                FROM users 
                WHERE email = ?;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    /**
     * @param $id
     * @return array
     */
    public function getUserById($id): array
    {
        $sql = "SELECT *
                FROM users 
                WHERE id = ?;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getSentAlertsSince2Hours(int $userId, array $types = null)
    {
        $sql = "SELECT DISTINCT (type)
                FROM alerts 
                WHERE user_id = ? 
                  AND sent = 1 
                  AND delivered_at > DATE_SUB(NOW(), INTERVAL 2 HOUR)";
        if ($types) {
            $in = str_repeat('?,', count($types) - 1) . '?';
            $sql .= " AND type IN ($in);";
        }

        $stmt = $this->connection->prepare($sql);
        array_unshift($types, $userId);
        $stmt->execute($types);
        return array_map(function ($element) {
            return $element['type'];
        }, $stmt->fetchAll());
    }
}