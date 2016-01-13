<?php

namespace AppBundle\Model\Db;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @return string The table name on which operations will be done
     */
    abstract public function getTable();

    /**
     * @param Connection $connection
     *
     * @throws \Exception
     */
    public function __construct(Connection $connection = null)
    {
        if ($connection) {
            $this->setConnection($connection);
        }
    }

    /**
     * @return The table name by default
     */
    public function __toString()
    {
        return $this->getTable();
    }

    /**
     * Define link to database
     *
     * @param $connection Connection
     */
    public function setConnection(Connection $connection)
    {
        $this->conn = $connection;
    }

    /**
     * formar params SQL LIMIT, OFFSET.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function formatSqlLimit($limit, $offset = 0)
    {
        if (!is_numeric($limit) or !is_numeric($offset)) {
            throw new InvalidArgumentException(sprintf(
                '%s method accepts only numeric values', __METHOD__
            ));
        }

        if ($limit and $offset) {
            return sprintf('LIMIT %d OFFSET %d', $limit, $offset);
        }

        return sprintf('LIMIT %d', $limit);
    }

    /**
     * Gerarate "question placeholders".
     *
     * @param $items
     * @return string
     */
    public static function buildPlaceholders($items)
    {
        if (is_array($items)) {
            $items = count($items);
        }
        # if is int
        return rtrim(str_repeat('?,', $items, ','));
    }

    /**
     * select one db row, where: ($indentifier == $value).
     *
     * @param string $value
     * @param string $indentifier
     * @return array
     */
    public function getOne($value, $indentifier = 'id')
    {
        $sql   = sprintf('SELECT * FROM %s WHERE %s = :value', $this->getTable(), $indentifier);
        $fetch = $this->conn->fetchAssoc($sql, ['value' => $value]);

        return $fetch;
    }

    /**
     * @see Connection::delete()
     * @param array $criteria (key-value pair)
     * @return int
     */
    public function delete(array $criteria)
    {
        return $this->conn->delete($this->getTable(), $criteria);
    }
}
