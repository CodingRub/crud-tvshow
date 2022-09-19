<?php

namespace Html\Form;

use Entity\Collection\GenreCollection;
use Entity\GenreTvshow;
use Entity\Poster;
use Entity\Tvshows;
use Exception\ParameterException;
use Html\StringEscaper;
use Html\WebPage;

class TvshowsForm
{
    use StringEscaper;

    private ?Tvshows $series;
    private ?Poster $posters;
    private ?GenreTvshow $genreTvshow;

    /**
     * @param ?Tvshows $series
     */
    public function __construct(?Tvshows $series = null, ?Poster $posters = null)
    {
        $this->series = $series;
        $this->posters = $posters;
    }

    /**
     * @return ?Tvshows
     */
    public function getTvshows(): ?Tvshows
    {
        return $this->series;
    }

    public function getPosters(): ?Poster
    {
        return $this->posters;
    }

    /**
     * @return GenreTvshow|null
     */
    public function getGenreTvshow(): ?GenreTvshow
    {
        return $this->genreTvshow;
    }

    /**
     * @param GenreTvshow|null $genreTvshow
     */
    public function setGenreTvshow(?GenreTvshow $genreTvshow): void
    {
        $this->genreTvshow = $genreTvshow;
    }



    /**
     * @param Tvshows|null $series
     */
    public function setTvshows(?Tvshows $series): void
    {
        $this->series = $series;
    }

    public function setPoster(?Poster $poster): void
    {
        $this->posters = $poster;
    }

    public function getHtmlForm(string $action): string
    {
        $serie = $this->getTvshows();
        $serieId = "";
        $serieName = "";
        $serieNameOri = "";
        $serieHomepage = "";
        $serieOverview = "";
        $seriePosterId = "";
        $serieGenre = "";
        $genres = GenreCollection::findAll();

        if ($serie != null) {
            $serieId = $serie->getId();
            $serieName = $this->escapeString($serie->getName());
            $seriePosterId = $serie->getPosterId();
            $serieNameOri = $serie->getOriginalName();
            $serieHomepage = $serie->getHomepage();
            $serieOverview = $serie->getText();
            $serieGenre = $serie->findGenreById($serieId);
        }
        $html = <<<HTML
                            <div class='form-popup' id='myForm'>
                        <form action="$action" method="post" enctype="multipart/form-data">
                            <p class="close"><i class="fa fa-times"></i></p>
                            <input type="hidden" name="id" value="$serieId">
                            <input type="hidden" name="idPoster" value="$seriePosterId">
                            <input type="text" name="name" value="$serieName" required>
                            <input type="text" name="nameori" value="$serieNameOri" required>
                            <input type="text" name="homepage" value="$serieHomepage" required>
                            <div class="form-checkbox">
        HTML;

        foreach ($genres as $genre) {
            $nameG = $genre->getName();
            $idG = $genre->getId();
            $checked = null;
            if ($serieGenre !== "") {
                foreach ($serieGenre as $g) {
                    if ($idG === $g->getId()) {
                        $checked = 'checked';
                    }
                }
            }
            $html .= <<<HTML
                                <div class="checkbox-group">
                                    <input type="checkbox" id="$nameG" name="$nameG" value="$idG" $checked>
                                    <label for="$nameG">$nameG</label>
                                </div>
            HTML;
        }
        $html .= <<<HTML
                            </div>
                            <textarea name="resume" cols="30" rows="10" required>$serieOverview</textarea>
                            <input type="file" name="poster">
                            <button>Enregistrer</button>
                        </form>
                    </div>
        HTML;
        return $html;
    }

    public function setEntityFromQueryString(): void
    {
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = null;
        }
        if (isset($_POST['idPoster']) && is_numeric($_POST['idPoster'])) {
            $idPoster = $_POST['idPoster'];
        } else {
            $idPoster = null;
        }
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $name = $_POST['name'];
        } else {
            throw new ParameterException();
        }
        if (isset($_POST['nameori']) && !empty($_POST['nameori'])) {
            $nameori = $_POST['nameori'];
        } else {
            throw new ParameterException();
        }
        if (isset($_POST['homepage']) && !empty($_POST['homepage'])) {
            $homepage = $_POST['homepage'];
        } else {
            throw new ParameterException();
        }
        if (isset($_POST['resume']) && !empty($_POST['resume'])) {
            $resume = $_POST['resume'];
        } else {
            throw new ParameterException();
        }
        $genres = GenreCollection::findAll();
        $genre = [];
        foreach ($genres as $g) {
            if (isset($_POST[$g->getName()])) {
                array_push($genre, $g->getId());
            }
        }
        if (isset($_FILES['poster'])) {
            if ($_FILES['poster']['size'] == 0) {
                $poster = "";
            } else {
                $poster = file_get_contents($_FILES['poster']['tmp_name']);
            }
        }

        $idPosterMax = Poster::getMaxId();
        $idGenreMax = GenreTvshow::getMaxId();
        $newGenres = GenreTvshow::create($id, $genre, $idGenreMax+1);
        $this->setGenreTvshow($newGenres);
        $newPosters = Poster::create($poster, $idPoster);
        $newTvshows = Tvshows::create($this->stripTagsAndTrim($name), $this->stripTagsAndTrim($nameori), $this->stripTagsAndTrim($homepage), $this->stripTagsAndTrim($resume), $idPoster, $id);
        $this->setPoster($newPosters);
        $this->setTvshows($newTvshows);
    }
}
