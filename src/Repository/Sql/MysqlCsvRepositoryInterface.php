<?php

declare(strict_types=1);

namespace App\Repository\Sql;

/**
 * Class MysqlCsvRepositoryInterface
 *
 * @package   App\Repository\Sql
 * @author    Lucas SIMONIN <lsimonin2@gmail.com>
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://lucasimonin.me/
 */
interface MysqlCsvRepositoryInterface
{
    /**
     * Description createTable function
     *
     * @param string   $name
     * @param string[] $attributes
     *
     * @return bool
     */
    public function createTable(string $name, array $attributes): bool;

    /**
     * Description loadCsv function
     *
     * @param string $name
     * @param string $path
     *
     * @return bool
     */
    public function loadCsv(string $name, string $path): bool;
}
