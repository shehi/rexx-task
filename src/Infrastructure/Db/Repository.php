<?php

declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\Entities\SerializableInterface;
use InvalidArgumentException;
use Iterator;
use PDO;
use PDOException;

class Repository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO('mysql:host=mariadb;dbname=rexx', 'root');
    }

    /**
     * @throws PDOException
     */
    public function fetchOneBy(string $table, string $by, mixed $value): ?array
    {
        $stmt = $this->connection->prepare(sprintf('SELECT * FROM %s WHERE %s = ?', $table, $by));
        $stmt->execute([$value]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    /**
     * @throws PDOException
     */
    public function fetchAll(string $table): ?Iterator
    {
        return $this->connection->query(sprintf('SELECT * FROM %s', $table))->getIterator();
    }

    /**
     * @throws PDOException
     */
    public function fetchRaw(string $query, array $params): ?array
    {
        if (preg_match_all('/\?/', $query, $placeholderMatches) && count($placeholderMatches[0]) !== count($params)) {
            throw new InvalidArgumentException('Provide proper SQL Statement with corresponding Params!');
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute(array_values($params));

        return $stmt->fetchAll();
    }

    /**
     * @throws PDOException
     */
    public function store(string $table, SerializableInterface $serializable): int
    {
        $data = $serializable->serialize();
        // Some ugliness as we don't have proper Hydration/ORM here.
        if (!$data['id']) {
            unset($data['id']);
        }

        $columns = array_keys($data);
        $bindNames = array_map(static fn (string $column) => sprintf(':%s', $column), $columns);

        $columnsImploded = implode(',', $columns);
        $bindNamesImploded = implode(',', $bindNames);
        $stmt = $this->connection->prepare(sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $columnsImploded, $bindNamesImploded));

        foreach ($data as $field => &$value) {
            $stmt->bindParam(sprintf(':%s', $field), $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        unset($value);

        try {
            $this->connection->beginTransaction();
            $stmt->execute();
            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();

            throw $e;
        }

        return $stmt->rowCount();
    }
}
