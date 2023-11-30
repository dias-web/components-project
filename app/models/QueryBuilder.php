<?php

namespace App\models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private PDO $pdo;
    private QueryFactory $queryFactory;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getOne($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where('id = :id', ['id' => $id]);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($table1, $table2): bool|array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table1)
            ->join(
                'LEFT',
                $table2,
                "{$table1}.id = {$table2}.user_id"
            );

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data)
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());

        return $this->pdo->lastInsertId();
    }

    public function update($table, $id, $data)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }
}