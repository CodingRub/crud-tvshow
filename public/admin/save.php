<?php
declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Poster;
use Exception\ParameterException;
use Html\Form\TvshowsForm;

$form = new TvshowsForm();
$form->setEntityFromQueryString();
$shows = $form->getTvshows();
$poster = $form->getPosters();
$genre = $form->getGenreTvshow();
$maxIdPoster = Poster::getMaxId() + 1;
$poster->save($maxIdPoster);
$shows->save($maxIdPoster);
foreach ($genre->getGenreId() as $g) {
    $genre->insert($shows->getId(), $g);
}
header('Location: ../index.php');
exit();


