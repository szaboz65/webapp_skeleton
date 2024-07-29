<?php

namespace App\Test\Traits;

/**
 * Database test.
 */
trait DBTestTrait
{
    /**
     * Asserts that a given table is the same as the given row.
     *
     * @param array $expectedRow Row expected to find
     * @param string $table Table to look into
     * @param string $key Table key
     * @param int $id The primary key
     * @param array|null $fields The columns
     * @param string $message Optional message
     *
     * @return void
     */
    protected function assertTableRowByKey(
        array $expectedRow,
        string $table,
        string $key,
        int $id,
        ?array $fields = null,
        string $message = ''
    ): void {
        $this->assertSame(
            $expectedRow,
            $this->getTableRowByKeyAndId($table, $key, $id, $fields ?: array_keys($expectedRow)),
            $message
        );
    }

    /**
     * Asserts that a given table contains a given row value.
     *
     * @param mixed $expected The expected value
     * @param string $table Table to look into
     * @param string $key Table key
     * @param int $id The primary key
     * @param string $field The column name
     * @param string $message Optional message
     *
     * @return void
     */
    protected function assertTableRowValueByKey(
        $expected,
        string $table,
        string $key,
        int $id,
        string $field,
        string $message = ''
    ): void {
        $actual = $this->getTableRowByKeyAndId($table, $key, $id, [$field])[$field];
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * Fetch row by ID.
     *
     * @param string $table Table name
     * @param string $key Table key
     * @param int $id The primary key value
     * @param array|null $fields The array of fields
     *
     * @throws \DomainException
     *
     * @return array Row
     */
    protected function getTableRowByKeyAndId(string $table, string $key, int $id, ?array $fields = null): array
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE `%s` = :id', $table, $key);
        $statement = $this->createPreparedStatement($sql);
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if (empty($row)) {
            throw new \DomainException(sprintf('Row not found: %s', $id));
        }

        if ($fields) {
            $row = array_intersect_key($row, array_flip($fields));
        }

        return $row;
    }

    /**
     * Asserts that a given table contains a given number of rows.
     *
     * @param string $table Table to look into
     * @param string $key Table key
     * @param int $id The id
     * @param string $message Optional message
     *
     * @return void
     */
    protected function assertTableRowExistsByKey(string $table, string $key, int $id, string $message = ''): void
    {
        $this->assertTrue((bool)$this->findTableRowByKeyAndId($table, $key, $id), $message);
    }

    /**
     * Asserts that a given table contains a given number of rows.
     *
     * @param string $table Table to look into
     * @param string $key Table key
     * @param int $id The id
     * @param string $message Optional message
     *
     * @return void
     */
    protected function assertTableRowNotExistsByKey(string $table, string $key, int $id, string $message = ''): void
    {
        $this->assertFalse((bool)$this->findTableRowByKeyAndId($table, $key, $id), $message);
    }

    /**
     * Fetch row by ID.
     *
     * @param string $table Table name
     * @param string $key Table key
     * @param int $id The primary key value
     *
     * @return array Row
     */
    protected function findTableRowByKeyAndId(string $table, string $key, int $id): array
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE `%s` = :id', $table, $key);
        $statement = $this->createPreparedStatement($sql);
        $statement->execute(['id' => $id]);

        return $statement->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Delete row by ID.
     *
     * @param string $table Table name
     * @param string $key Table key
     * @param int $id The primary key value
     *
     * @return bool
     */
    protected function deleteTableRowByKeyAndId(string $table, string $key, int $id): bool
    {
        $sql = sprintf('DELETE FROM `%s` WHERE `%s` = :id', $table, $key);
        $statement = $this->createPreparedStatement($sql);
        $statement->execute(['id' => $id]);

        return (bool)$statement->fetch(\PDO::FETCH_ASSOC);
    }
}
