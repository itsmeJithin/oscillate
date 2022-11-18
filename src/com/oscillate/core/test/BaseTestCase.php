<?php


namespace com\oscillate\core\test;


use com\oscillate\core\exception\CoreException;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Creating mysqli connection
     * @return false|\mysqli|null
     */
    private function getConnection()
    {
        /**
         * TODO: These fields can be read from env variables or a common file
         */
        $DB_HOST = "localhost";
        $DB_USER = "root";
        $DB_PASSWORD = "root@mac";
        $DB_NAME = "oscillate";
        $connection = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
        return $connection;
    }

    /**
     * @param string $tableName
     * @param array $map
     * @return \stdClass
     * @throws CoreException
     */
    public function assertDatabaseHelper($tableName, $map)
    {
        $returnObject = new \stdClass();
        if (!isset($tableName) || !isset($map)) {
            throw new CoreException('Database name and field map is required');
        }
        $connection = $this->getConnection();
        $query = "SELECT * from $tableName where 1=1 ";
        foreach ($map as $key => $value) {
            $query .= 'and ' . $key . "='" . $value . "'";
        }
        $result = mysqli_query($connection, $query);
        if (!$result) {
            throw new CoreException("SQL_ERROR", mysqli_error($connection));
        }
        mysqli_close($connection);
        $returnObject->result = $result;
        $returnObject->query = $query;
        return $returnObject;
    }

    /**
     * @param string $tableName
     * @param array $map
     * @throws CoreException
     */
    public function assertDatabaseHas($tableName, $map)
    {
        $result = $this->assertDatabaseHelper($tableName, $map);
        $message = "\033[31m Expected data not found on table `$tableName`. \n Query:$result->query \n\033[0m";
        $this->assertTrue(mysqli_num_rows($result->result) > 0, $message);
    }

    /**
     * @param string $tableName
     * @param array $map
     * @throws CoreException
     */
    public function assertDatabaseHasNot($tableName, $map)
    {
        $result = $this->assertDatabaseHelper($tableName, $map);
        $message = "\033[31m Expected data not found on table `$tableName`. \nQuery: $result->query \n\033[0m";
        $this->assertEquals(0, mysqli_num_rows($result->result), $message);
    }

    /**
     * clearing tables or table
     *
     * @param array|string $tableNames
     * @throws CoreException
     */
    public function clearDBTable($tableNames)
    {
        if (!isset($tableNames)) {
            throw new CoreException('table name  is required');
        }
        // For clearing multiple table in a single command
        if (is_array($tableNames)) {
            foreach ($tableNames as $tableName) {
                $this->clearDBTable($tableName);
            }
        } else {
            $connection = $connection = $this->getConnection();
            mysqli_query($connection, "SET FOREIGN_KEY_CHECKS = 0");
            mysqli_query($connection, "TRUNCATE table $tableNames");
            mysqli_query($connection, "SET FOREIGN_KEY_CHECKS = 1");
        }
    }

    /**
     * dropping tables
     *
     * @param array|string $tableNames
     * @throws CoreException
     */
    public function dropTable($tableNames)
    {
        if (!isset($tableNames)) {
            throw new CoreException('table name  is required');
        }
        // For clearing multiple table in a single command
        if (is_array($tableNames)) {
            foreach ($tableNames as $tableName) {
                $this->dropTable($tableName);
            }
        } else {
            $connection = $connection = $this->getConnection();
            mysqli_query($connection, "SET FOREIGN_KEY_CHECKS = 0");
            mysqli_query($connection, "drop table if exists $tableNames");
            mysqli_query($connection, "SET FOREIGN_KEY_CHECKS = 1");
        }
    }

    /**
     * @param string $tableName
     * @param string $field
     * @param mixed $value
     * @throws CoreException
     */
    public function deleteElement($tableName, $field, $value)
    {
        if (!isset($tableName)) {
            throw new CoreException('table name  is required');
        }
        $connection = $this->getConnection();
        mysqli_query($connection, "DELETE FROM $tableName WHERE $field='$value'");

    }

}