<?php

namespace App\Domain\Repository;

use PDO;

/**
 * Repository.
 */
class StationPayloadRepository
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
     * Bulk inserts payloads data
     *
     * @param array $parsedPayloads
     */
    public function storeAll(array $parsedPayloads)
    {
        $sql = "INSERT INTO station_payloads 
                (battery, solar, rain, air_avg, air_mn, air_mx, rh_mn, rh_mx, rh_avg, dt_avg, dt_mn, dt_mx, dew_avg, dew_mn, vpd_avg, vpd_mn, leaf) 
                VALUES ";

        $paramArray = array();
        $sqlArray = array();
        foreach ($parsedPayloads as $payload) {
            $sqlArray[] = '(' . implode(',', array_fill(0, count($payload), '?')) . ')';

            foreach ($payload as $element) {
                $paramArray[] = $element;
            }
        }

        $sql .= implode(',', $sqlArray);

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($paramArray);
    }
}