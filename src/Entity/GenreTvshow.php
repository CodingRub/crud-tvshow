<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use PDO;

class GenreTvshow
{
    private ?int $id = null;
    private array $genreId;
    private ?int $tvShowId = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getGenreId(): array
    {
        return $this->genreId;
    }

    /**
     * @param int $genreId
     */
    public function setGenreId(array $genreId): void
    {
        $this->genreId = $genreId;
    }

    /**
     * @return int
     */
    public function getTvShowId(): int
    {
        return $this->tvShowId;
    }

    /**
     * @param int $tvShowId
     */
    public function setTvShowId(?int $tvShowId): void
    {
        $this->tvShowId = $tvShowId;
    }

    /**
     * Permet de récupérer un array des genres d'une série
     * 
     * @param int $id
     * @return array
     */
    public static function findById(int $id): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT id, genreId, tvShowId
          FROM tvshow_genre
          WHERE tvShowId = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res;
    }

    /**
     * Permet de supprimer tout les genres d'une série grâce à son id
     * 
     * @param int $id
     * @return void
     */
    public static function deleteById(int $id): void
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          DELETE FROM tvshow_genre
          WHERE tvShowId = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
    }

    /**
     * Supprime tout les genres d'une série et insére les nouveaux genres
     * 
     * @param int $tvShowId
     * @param int $genreId
     * @return GenreTvshow
     */
    public function insert(int $tvShowId, int $genreId): GenreTvshow
    {
        $this::deleteById($tvShowId);
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
                INSERT INTO tvshow_genre (id, genreId, tvShowId)
                VALUES (:id, :genreId, :tvShowId)
        SQL
        );
        $stmt->execute([':id' => GenreTvshow::getMaxId()+1, ':genreId' => $genreId, ':tvShowId' => $tvShowId]);
        $this->setId(MyPDO::getInstance()->lastInsertId());
        return $this;
    }

    /**
     * Permet de récuperer le dernier id inséré dans la table
     * 
     * @return int
     */
    public static function getMaxId(): int
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            SELECT MAX(id)
            FROM tvshow_genre
        SQL
        );
        $stmt->execute();
        $res = $stmt->fetch();
        return $res['MAX(id)'];
    }

    /**
     * Permet de créer un nouveau GenreTvShow
     * 
     * @param ?int $tvShowId
     * @param array $genreId
     * @param ?int $id
     * @return GenreTvshow
     */
    public static function create(?int $tvShowId, array $genreId, ?int $id = null): GenreTvshow
    {
        $newGenre = new GenreTvshow();
        $newGenre->setId($id);
        $newGenre->setTvShowId($tvShowId);
        $newGenre->setGenreId($genreId);
        return $newGenre;
    }
}