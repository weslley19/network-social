<?php

namespace core;

use \core\Database;
use \ClanCats\Hydrahon\Builder;
use \ClanCats\Hydrahon\Query\Sql\FetchableInterface;

class Model
{
    /** @var [type] */
    protected static $_h;

    /**
     * Connect construct
     */
    public function __construct()
    {
        self::_checkH();
    }

    /**
     *
     * @return void
     */
    public static function _checkH(): void
    {
        if (self::$_h == null) {
            $connection = Database::getInstance();
            self::$_h = new Builder('mysql', function ($query, $queryString, $queryParameters) use ($connection) {
                $statement = $connection->prepare($queryString);
                $statement->execute($queryParameters);

                if ($query instanceof FetchableInterface) {
                    return $statement->fetchAll(\PDO::FETCH_ASSOC);
                }
            });
        }

        self::$_h = self::$_h->table(self::getTableName());
    }

    /**
     *
     * @return string
     */
    public static function getTableName(): string
    {
        $className = explode('\\', get_called_class());
        $className = end($className);
        return strtolower($className) . 's';
    }

    /**
     *
     * @param array $fields
     * @return object
     */
    public static function select($fields = []): object
    {
        self::_checkH();
        return self::$_h->select($fields);
    }

    /**
     *
     * @param array $fields
     * @return object
     */
    public static function insert($fields = []): object
    {
        self::_checkH();
        return self::$_h->insert($fields);
    }

    /**
     *
     * @param array $fields
     * @return object
     */
    public static function update($fields = []): object
    {
        self::_checkH();
        return self::$_h->update($fields);
    }

    /**
     *
     * @return object
     */
    public static function delete(): object
    {
        self::_checkH();
        return self::$_h->delete();
    }
}
