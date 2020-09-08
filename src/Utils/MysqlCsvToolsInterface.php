<?php

declare(strict_types=1);

namespace App\Utils;



/**
 * Interface MysqlCsvToolsInterface
 *
 * @package   App\Utils
 * @author    Agence Dn'D <contact@dnd.fr>
 * @copyright 2004-present Agence Dn'D
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.dnd.fr/
 */
interface MysqlCsvToolsInterface
{
    /**
     * Description generateTable function
     *
     * @param string $name
     * @param string $path
     *
     * @return string|null
     */
    public function generateTable(string $name, string $path): ?string;

    /**
     * Description readCsv function
     *
     * @param string $path
     *
     * @return bool
     */
    public function readCsv(string $path): bool;

    /**
     * Description getHeaderCsv function
     *
     * @return array
     */
    public function getHeaderCsv(): array;

    /**
     * Description getDataCsv function
     *
     * @param string $name
     * @param string $path
     *
     * @return string
     */
    public function loadDataCsv(string $name, string $path): string;
}
