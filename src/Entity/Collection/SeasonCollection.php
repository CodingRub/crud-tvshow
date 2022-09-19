<?php

namespace Entity\Collection;

use Database\MyPdo;
use Entity\Season;
use PDO;

class SeasonCollection
{
    /**
     * Permet de récupérer les saisons par séries
     * 
     * @param int $showsId
     * @return array
     */
    public static function findByShowsId(int $showsId): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM season
          WHERE tvShowId = :id
        SQL
        );
        $stmt->execute([':id' => $showsId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Season::class);
    }
}