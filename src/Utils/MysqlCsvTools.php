<?php

declare(strict_types=1);

namespace App\Utils;

use App\Repository\Sql\MysqlCsvRepositoryInterface;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Box\Spout\Reader\CSV\Reader;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Box\Spout\Reader\CSV\Sheet;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Cell;

/**
 * Class MysqlCsvTools
 *
 * @package   App\Utils
 * @author    Agence Dn'D <contact@dnd.fr>
 * @copyright 2004-present Agence Dn'D
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.dnd.fr/
 */
class MysqlCsvTools implements MysqlCsvToolsInterface
{
    /**
     * Description $reader field
     *
     * @var Reader $reader
     */
    protected Reader $reader;
    /**
     * Description $mysqlCsvRepository field
     *
     * @var MysqlCsvRepositoryInterface $mysqlCsvRepository
     */
    protected MysqlCsvRepositoryInterface $mysqlCsvRepository;
    /**
     * Description $csvPathFolder field
     *
     * @var string $csvPathFolder
     */
    protected string $csvPathFolder;
    /**
     * Description $csvMysqlFolder field
     *
     * @var string $csvMysqlFolder
     */
    protected string $csvMysqlFolder;

    /**
     * MysqlCsvTools constructor
     *
     * @param MysqlCsvRepositoryInterface $mysqlCsvRepository
     * @param string                      $csvPathFolder
     * @param string                      $csvMysqlFolder
     */
    public function __construct(
        MysqlCsvRepositoryInterface $mysqlCsvRepository,
        string $csvPathFolder,
        string $csvMysqlFolder
    ) {
        $this->mysqlCsvRepository = $mysqlCsvRepository;
        $this->csvPathFolder      = $csvPathFolder;
        $this->csvMysqlFolder     = $csvMysqlFolder;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @param string $path
     *
     * @return string|null
     */
    public function generateTable(string $name, string $path): ?string
    {
        /** @var string $path */
        $path = sprintf('%s/%s', $this->csvPathFolder, $path);
        /** @var bool $result */
        $result = $this->readCsv($path);
        if (false === $result) {
            return sprintf('The csv %s, cannot be read please check logs.', $path);
        }
        /** @var string[] $attributes */
        $attributes = $this->getHeaderCsv();
        $this->closeCsv();
        /** @var bool $result */
        $result = $this->mysqlCsvRepository->createTable($name, $attributes);
        if (false === $result) {
            return sprintf('The table %s, cannot be created please check logs.', $name);
        }

        return sprintf('ok'); // todo change message
    }

    /**
     * {@inheritDoc}
     *
     * @param string $path
     *
     * @return bool
     */
    public function readCsv(string $path): bool
    {
        try {
            /** @var Reader $reader */
            $this->reader = ReaderFactory::createFromType(Type::CSV);
        } catch (UnsupportedTypeException $e) {
            // todo add log
            return false;
        }
        try {
            $this->reader->open($path);
        } catch (IOException $e) {
            // todo add log
            return false;
        }

        return true;
    }

    public function closeCsv(): bool
    {
        if (null === $this->reader) {
            return true;
        }
        $this->reader->close();

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getHeaderCsv(): array
    {
        if (null === $this->reader) {
            throw new \InvalidArgumentException('Please call readCsv before to use this function');
        }
        /** @var string[] $headers */
        $headers = [];
        try {
            /** @var Sheet $sheet */
            foreach ($this->reader->getSheetIterator() as $sheet) {
                /** @var Row $row */
                foreach ($sheet->getRowIterator() as $row) {
                    /** @var Cell $cell */
                    foreach ($row->getCells() as $cell) {
                        $headers[] = $cell->getValue();
                    }
                    break;
                }
            }
        } catch (ReaderNotOpenedException $e) {
        }

        return $headers;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @param string $path
     *
     * @return string
     */
    public function loadDataCsv(string $name, string $path): string
    {
        /** @var string $path */
        $path = sprintf('%s/%s', $this->csvMysqlFolder, $path);
        /** @var bool $result */
        $result = $this->mysqlCsvRepository->loadCsv($name, $path);

        if (false === $result) {
            return sprintf('The csv %s, cannot be loaded in the table %s please check logs.', $path, $name);
        }

        return sprintf('ok'); // todo change message
    }
}
