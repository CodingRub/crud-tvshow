<?php

namespace Entity;

use Database\MyPdo;
use Entity\Collection\SeasonCollection;
use Entity\Exception\EntityNotFoundException;
use PDO;

class Tvshows
{
    private ?int $id;
    private string $name;
    private string $originalName;
    private string $homepage;
    private string $overview;
    private ?int $posterId;

    /**
     * @param ?int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $originalName
     */
    public function setOriginalName(string $originalName): void
    {
        $this->originalName = $originalName;
    }

    /**
     * @param string $homepage
     */
    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    /**
     * @param string $overview
     */
    public function setOverview(string $overview): void
    {
        $this->overview = $overview;
    }

    /**
     * @param ?int $posterId
     */
    public function setPosterId(?int $posterId): void
    {
        $this->posterId = $posterId;
    }



    /**
     * @return ?int
     */
    public function getId(): ?int
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
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getHomepage(): string
    {
        return $this->homepage;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->overview;
    }

    /**
     * @return ?int
     */
    public function getPosterId(): ?int
    {
        return $this->posterId;
    }

    /**
     * Permet de récuperer toutes les séries en fonction de leur genre
     * 
     * @param int $id
     * @throws EntityNotFoundException
     * @return array
     */
    public static function findGenreById(int $id): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            SELECT g.id, g.name
            FROM genre g
            JOIN tvshow_genre tg ON (g.id = tg.genreId)
            WHERE tg.tvShowId = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_CLASS, Genre::class);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res;
    }

    /**
     * Permet de récupérer une série en fonction de son id
     * 
     * @param int $id
     * @throws EntityNotFoundException
     * @return Tvshows
     */
    public static function findById(int $id): Tvshows
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM tvshow
          WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_CLASS, Tvshows::class);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res[0];
    }


    /**
     * Permet de récuperer un poster en fonction de son id
     * 
     * @param int $id
     * @throws EntityNotFoundException
     * @return Poster
     */
    public static function findPosterById(int $id): Poster
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
     * Permet de récuperer toutes les saisons d'une série
     * 
     * @return array
     */
    public function getSeasons(): array
    {
        return SeasonCollection::findByShowsId($this->getId());
    }


    /**
     * Permet de supprimer la série ainsi que l'objet
     * 
     * @return Tvshows
     */
    public function delete(): Tvshows
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            DELETE FROM tvshow
            WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $this->getId()]);
        $this->setId(null);
        return $this;
    }

    /**
     * Permet d'insérer dans la base de donnée une série
     * 
     * @param int $maxIdPoster
     * @return Tvshows
     */
    public function insert(int $maxIdPoster): Tvshows
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
                INSERT INTO tvshow (id, name, originalName, homepage, overview, posterId)
                VALUES (:id, :name, :originalName, :homepage, :overview, :posterId)
        SQL
        );
        $stmt->execute([':id' => $this->id, ':name' => $this->name, ':originalName' => $this->originalName, ':homepage' => $this->homepage, ':overview' => $this->overview, ':posterId' => $maxIdPoster]);
        $this->setId(MyPDO::getInstance()->lastInsertId());
        return $this;
    }

    /**
     * Permet de mettre à jour une série dans la base de donnée
     * 
     * @return Tvshows
     */
    public function update(): Tvshows
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            UPDATE tvshow
            SET id = :id, name = :name, originalName = :originalName, homepage = :homepage, overview = :overview, posterId = :posterId
            WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $this->id, ':name' => $this->name, ':originalName' => $this->originalName, ':homepage' => $this->homepage, ':overview' => $this->overview, ':posterId' => $this->posterId]);
        return $this;
    }

    /**
     * Permet soit d'insérer ou de mettre à jour en fonction de si l'id est présent ou pas
     * 
     * @return Tvshows
     */
    public function save($maxIdPoster): Tvshows
    {
        if ($this->getId() == null) {
            $this->insert($maxIdPoster);
        } else {
            $this->update();
        }
        return $this;
    }

    /**
     * Permet de créer un nouvel objet Tvshows
     * 
     * @param string $name
     * @param string $nameOri
     * @param string $homepage
     * @param string $overview
     * @param ?int $posterId
     * @param ?int $id
     * @return Tvshows
     */
    public static function create(string $name, string $nameOri, string $homepage, string $overview, ?int $posterId = null, ?int $id = null): Tvshows
    {
        $newSeries = new Tvshows();
        $newSeries->setName($name);
        $newSeries->setId($id);
        $newSeries->setOriginalName($nameOri);
        $newSeries->setHomepage($homepage);
        $newSeries->setOverview($overview);
        $newSeries->setPosterId($posterId);
        return $newSeries;
    }

    /**
     * Permet de récupérer le dernier id inséré dans la table tvshow
     * 
     * @return int
     */
    public static function getMaxId(): int
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            SELECT MAX(id)
            FROM tvshow
        SQL
        );
        $stmt->execute();
        $res = $stmt->fetch();
        return $res['MAX(id)'];
    }
    
}