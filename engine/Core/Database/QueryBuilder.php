<?php

namespace Engine\Core\Database;

class QueryBuilder
{
    protected array $sql = [];

    public array $values = [];


    /**
     * @param string $fields
     * @return QueryBuilder
     */
    public function select(string $fields = '*'): static
    {
        $this->reset();
        $this->sql['select'] = "SELECT {$fields} ";

        return $this;
    }

    public function delete(): static
    {
        $this->reset();
        $this->sql['delete'] = "DELETE ";

        return $this;
    }

    public function where($column, $value, $operator = '='): static
    {
        $this->sql['where'][] = "{$column} {$operator} ?";
        $this->values[] = $value;

        return $this;
    }

    public function orderBy($field, $order): static
    {
        $this->sql['order_by'] = "ORDER BY {$field} {$order}";

        return $this;
    }

    public function sql(): string
    {
        $sql = '';

        if (!empty($this->sql)) {
            foreach ($this->sql as $key => $value) {
                if ($key == 'where') {
                    $sql .= 'WHERE ';
                    foreach ($value as $where) {
                        $sql .= $where;
                        if (count($value) > 1 and next($value)) {
                            $sql .= ' AND';
                        }
                    }
                } else {
                    $sql .= $value;
                }
            }
        }

        return $sql;
    }

    public function from(string $table): static
    {
        $this->sql['from'] = "FROM {$table}";

        return $this;
    }

    public function reset(): void
    {
        $this->sql = [];
        $this->values = [];
    }

}