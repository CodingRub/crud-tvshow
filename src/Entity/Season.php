<?php

namespace Entity;

use Database\MyPdo;
use Entity\Collection\EpisodeCollection;
use Entity\Exception\EntityNotFoundException;
use PDO;

class Season
{
    private int $id;
    private int $tvShowId;
    private string $name;
    private int $seasonNbr;
    private ?int $posterId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTvShowId(): int
    {
        return $this->tvShowId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getSeasonNbr(): int
    {
        return $this->seasonNbr;
    }

    /**
     * @return ?int
     */
    public function getPosterId(): ?int
    {
        return $this->posterId;
    }

    /**
     * Permet de récuperer une saison par rapport à son id
     * 
     * @param int $id
     * @throws EntityNotFoundException
     * @return Season
     */
    public static function findById(int $id): Season
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM season
          WHERE id = :id
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_CLASS, Season::class);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res[0];
    }

    /**
     * Permet de récupérer tout les épisodes d'une saison
     * 
     * @return array
     */
    public function getEpisodes(): array
    {
        return EpisodeCollection::findBySeasonId($this->getId());
    }
}