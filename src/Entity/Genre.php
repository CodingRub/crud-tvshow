<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use PDO;

class Genre
{
    private int $id;
    private string $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Permet de retourner grâce à l'id le genre
     * 
     * @param int $id
     * @return Genre
     */
    public static function findById(int $id): Genre
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM genre
          WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_CLASS, Genre::class);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res[0];
    }
}