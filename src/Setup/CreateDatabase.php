<?php

namespace App\Setup;

class CreateDatabase
{
    public const DATABASE_NAME = 'fossil_database';

    public function createDatabase(string $username, string $password, string $host, string $port): string
    {
        $connection = $this->createPDOConnection($username, $password, $host, $port);

        if ($this->checkIfDatabaseExists($connection)) {
            return 'Database already exists';
        }

        $sql = sprintf('CREATE DATABASE %s;', self::DATABASE_NAME);

        $connection->exec($sql);

        if ($this->checkIfDatabaseExists($connection)) {
            return 'Database successfully created';
        }

        throw new \RuntimeException('Cannot create Database');
    }

    private function checkIfDatabaseExists(\PDO $connection): bool
    {
        $sql = sprintf('SHOW DATABASES LIKE "%s";', self::DATABASE_NAME);

        $result = $connection->query($sql)->fetchColumn();

        if (is_string($result)) {
            return true;
        }

        return false;
    }

    private function createPDOConnection(string $username, string $password, string $host, string $port): \PDO
    {
        $connectionString = sprintf('mysql:host=%s;port=%s;', $host, $port);

        return new \PDO(
            $connectionString,
            $username,
            $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]
        );
    }
}
