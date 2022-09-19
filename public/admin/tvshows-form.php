<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Poster;
use Entity\Tvshows;
use Exception\ParameterException;
use Html\AppWebPage;
use Html\Form\TvshowsForm;
use Html\WebPage;

try {
    $shows = null;
    $genres = null;
    $poster = null;
    if (isset($_GET['serieId'])) {
        if (ctype_digit($_GET['serieId'])) {
            $shows = Tvshows::findById(intval($_GET['serieId']));
            $poster = Poster::findById(intval($_GET['serieId']));

        } else {
            throw new ParameterException();
            header('Location: ../index.php')
        }
    }
} catch (ParameterException) {
    http_response_code(400);
} catch (EntityNotFoundException) {
    http_response_code(404);
} catch (Exception) {
    http_response_code(500);
}
$webpage = new AppWebPage();
$webpage->setTitle("Formulaires");
$form = new TvshowsForm($shows, $poster);
$webpage->appendContent("
        {$form->getHtmlForm("save.php")}
");
echo $webpage->toHTML();
