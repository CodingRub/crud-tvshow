<?php

namespace Entity\Collection;

use Database\MyPdo;
use Entity\Genre;
use Entity\Tvshows;
use PDO;

class GenreCollection
{
    /**
     * Permet de récuperer tout les genres existants dans la table
     * 
     * @return array
     */
    public static function findAll(): array
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<SQL
          SELECT *
          FROM genre
          ORDER BY name 
        SQL
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Genre::class);
    }
}