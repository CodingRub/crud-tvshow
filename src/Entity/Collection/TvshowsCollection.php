<?php

namespace Entity\Collection;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use Entity\Genre;
use Entity\Tvshows;
use PDO;

class TvshowsCollection
{
    /**
     * Permet de récuperer toutes les séries existantes dans la base de donnée
     * 
     * @return array
     */
    public static function findAll(): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM tvshow
          ORDER BY name
        SQL
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Tvshows::class);
    }

    /**
     * Permet de récuperer toutes les séries par genre
     * 
     * @param int $id
     * @throws EntityNotFoundException
     * @return array
     */
    public static function findByGenreId(int $id): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
            SELECT g.id, g.name, g.originalName, g.homepage, g.overview, g.posterId
            FROM tvshow g
            INNER JOIN tvshow_genre tg ON (g.id = tg.tvShowId)
            WHERE tg.genreId = :id
            ORDER BY name
        SQL
        );
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetchAll(PDO::FETCH_CLASS, Tvshows::class);
        if (sizeof($res) == 0) {
            throw new EntityNotFoundException();
        }
        return $res;
    }
}