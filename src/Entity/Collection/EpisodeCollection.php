<?php

namespace Entity\Collection;
use Database\MyPdo;
use Entity\Episode;
use PDO;

class EpisodeCollection
{
    /**
     * Permet de récupérer la liste des épisodes par saison
     * 
     * @param int $seasonId
     * @return array
     * 
     */
    public static function findBySeasonId(int $seasonId): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM episode
          WHERE seasonId = :id
        SQL
        );
        $stmt->execute([':id' => $seasonId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Episode::class);
    }
}