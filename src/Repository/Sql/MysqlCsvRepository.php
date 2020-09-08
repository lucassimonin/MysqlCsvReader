<?php

declare(strict_types=1);

namespace App\Repository\Sql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\ResultStatement;

/**
 * Class MysqlCsvRepository
 *
 * @package   App\Repository\Sql
 * @author    Lucas SIMONIN <lsimonin2@gmail.com>
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://lucasimonin.me/
 */
class MysqlCsvRepository implements MysqlCsvRepositoryInterface
{
    /**
     * Description $sqlConnection field
     *
     * @var Connection $sqlConnection
     */
    private Connection $sqlConnection;

    /**
     * MysqlCsvRepository constructor
     *
     * @param Connection $sqlConnection
     */
    public function __construct(
        Connection $sqlConnection
    ) {
        $this->sqlConnection = $sqlConnection;
    }

    /**
     * {@inheritDoc}
     *
     * @param string   $name
     * @param string[] $attributes
     *
     * @return bool
     */
    public function createTable(string $name, array $attributes): bool
    {
        /** @var string $attributesString */
        $attributesString = implode(' VARCHAR(255) NOT NULL,', $attributes); // Todo manage type of attribute
        $attributesString .= ' VARCHAR(255) NOT NULL';
        /** @var string $sql */
        $sql = <<<SQL
CREATE TABLE $name (
    $attributesString
);
SQL;
        try {
            /** @var ResultStatement $result */
            $result = $this->sqlConnection->executeQuery($sql);
            // todo check result
        } catch (DBALException $e) {
            // Todo log

            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @param string $path
     *
     * @return bool
     */
    public function loadCsv(string $name, string $path): bool
    {
        /** @var string $sql */
        $sql = <<<SQL
LOAD DATA INFILE '$path'
INTO TABLE $name 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
SQL;
        try {
            /** @var ResultStatement $result */
            $result = $this->sqlConnection->executeQuery($sql);
        } catch (DBALException $e) {
            // Todo log
            var_dump($e->getMessage());die;
            return false;
        }

        return true;
    }
}
