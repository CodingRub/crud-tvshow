<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Tvshows;
use Html\AppWebPage;
use Html\WebPage;

if (!isset($_GET['seriesId']) || !ctype_digit($_GET['seriesId'])) {
    header('Location: index.php');
    exit();
}

$tvshowsId = (int)$_GET['seriesId'];

$tvshow= null;
try {
    $tvshow= Tvshows::findById($tvshowsId);
} catch (EntityNotFoundException $e) {
    http_response_code(404);
    exit();
}
$name = $tvshow->getName();
$nameOri = $tvshow->getOriginalName();
$desc = $tvshow->getText();

$webpage = new AppWebPage();

$webpage->setTitle($name);
$webpage->appendCssUrl("css/style.css");

$webpage->appendContent(
    <<<HTML
        <header class="header">
        <h1>Série Tv: $name</h1>
    </header>
    <div class="mainEpSa">
        <div class="image-container" id="1st">
            <img src='poster.php?coverId={$tvshow->getPosterId()}' alt='{$name}'>
            <div class="text-info">
                <h3>$name</h3>
                <h3>$nameOri</h3>
                <p>$desc</p>
            </div>
        </div>
    <div class="wrapper">
HTML
);
$seasons = $tvshow->getSeasons();

foreach ($seasons as $season ) {
    $name = WebPage::escapeString($season->getName());
    $id = $season->getId();
    $webpage->appendContent("
<div class='box'>
                    <img src='poster.php?coverId={$season->getPosterId()}'>
                    <div class='overlay'>
                        <div class='text'>
                            <h3><a href='episode.php?seasonsId=$id'>$name</a></h3>
                        </div>
                    </div>
</div>
    ");
}

$webpage->appendContent(
    <<<HTML
        </div>
</div>
HTML);

echo $webpage->toHTML();