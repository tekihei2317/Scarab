<?php

namespace Scarab\Database;

use PDO;
use Scarab\Database\PDO\PdoConnector;
use Scarab\Database\QueryBuilder;

class DB
{
    public PDO $connection;

    public function __construct()
    {
        $this->connection = (new PdoConnector())->connect();
    }

    /**
     * 指定したテーブルをセットしたクエリビルダを返す
     */
    public static function table(string $tableName)
    {
        return new QueryBuilder($tableName);
    }

    /**
     * クエリを実行する
     */
    public static function execute(string $query): array
    {
        $db = new self;
        return $db->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}
