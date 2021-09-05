<?php declare(strict_types = 1);

namespace Scarab\Trial\Database;

/**
 * SQLを構築する
 */
class QueryBuilder
{
    private string $table;

    private string $query;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * SELECTするカラムをセットする
     *
     * @param array $columns
     * @return $this
     */
    public function select(array $columns = ['*']): self
    {
        $columns = implode(', ', $columns);
        $this->query = "SELECT ${columns} FROM {$this->table}";

        return $this;
    }

    /**
     * WHERE条件を追加する
     *
     * @param string $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where(string $column, $operator = null, $value = null): self
    {
        // TODO: SQLインジェクション対策
        [$operator, $value] = $this->prepareValueAndOperator($operator, $value, func_num_args() === 2);

        if (str_contains($this->query, 'WHERE')) {
            $this->query .= " AND ${column} ${operator} ${value}";
        } else {
            $this->query .= " WHERE ${column} ${operator} ${value}";
        }

        return $this;
    }

    private function prepareValueAndOperator($operator = null, $value = null, bool $useDefault): array
    {
        if ($useDefault) {
            return ['=', $operator];
        } else {
            return [$operator, $value];
        }
    }

    /**
     * SQLクエリを取得する
     *
     * @return string
     */
    public function toSql(): string
    {
        return $this->query;
    }
}
