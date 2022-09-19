<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use PDO;

class Poster
{
    private ?int $id = null;
    private ?string $jpeg = null;

    /**
     * @param ?int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param ?string $jpeg
     */
    public function setJpeg(?string $jpeg): void
    {
        $this->jpeg = $jpeg;
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getJpeg(): ?string
    {
        return $this->jpeg;
    }

    /**
     * Permet de récuperer un Poster à partir d'un id
     * 
     * @param int $id
     * @return Poster
     */
    public static function findById(int $id): Poster
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM poster
          WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_CLASS, Poster::class);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res[0];
    }

    /**
     * Permet de récuperer le dernier id inséré dans la table poster
     * 
     * @return int
     */
    public static function getMaxId(): int
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            SELECT MAX(id)
            FROM poster
        SQL
        );
        $stmt->execute();
        $res = $stmt->fetch();
        return $res['MAX(id)'];
    }

    /**
     * Permet d'insérer dans la base de donnée un nouveau Poster
     * 
     * @param int $maxIdPoster
     * @return Poster
     */
    public function insert(int $maxIdPoster): Poster
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
                INSERT INTO poster (id, jpeg)
                VALUES (:id, :jpeg)
        SQL
        );
        $stmt->execute([':id' => $this->id, ':jpeg' => $maxIdPoster]);
        $this->setId(MyPDO::getInstance()->lastInsertId());
        return $this;
    }

    /**
     * Permet de mettre à jour un Poster dans la base de donnée
     * 
     * @return Poster
     */
    public function update(): Poster
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            UPDATE poster
            SET id = :id, jpeg = :jpeg
            WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $this->id, ':jpeg' => $this->jpeg]);
        return $this;
    }

    /**
     * Permet de soit insérer soit mettre à jour en fonction de la présence d'un id dans la base de donnée
     * 
     * @return Poster
     */
    public function save($maxIdPoster): Poster
    {
        if ($this->getId() == null) {
            $this->insert($maxIdPoster);
        } else {
            $this->update();
        }
        return $this;
    }

    /**
     * Permet de créer un nouvel objet Poster
     * 
     * @param ?string $jpeg
     * @param ?int $id
     * @return Poster
     */
    public static function create(?string $jpeg, ?int $id = null): Poster
    {
        $newPoster = new Poster();
        $newPoster->setId($id);
        $newPoster->setJpeg($jpeg);
        return $newPoster;
    }
}