<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Season;
use Entity\Tvshows;
use Html\AppWebPage;
use Html\WebPage;

if (!isset($_GET['seasonsId']) || !ctype_digit($_GET['seasonsId'])) {
    header('Location: index.php');
    exit();
}

$seasonId = (int)$_GET['seasonsId'];

$saison= null;
try {
    $saison = Season::findById($seasonId);
} catch (EntityNotFoundException $e) {
    http_response_code(404);
    exit();
}
$name = $saison->getName();

$webpage = new AppWebPage();

$webpage->setTitle($name);

$webpage->appendCssUrl("css/style.css");


$serie = Tvshows::findById($saison->getTvShowId());
$nameSerie = $serie->getName();
$webpage->appendContent(
    <<<HTML
    <header class="header">
        <h1>Série Tv: $name</h1>
    </header>
    <div class="mainEpSa">
        <div class="image-container" id="1st">
            <img src='poster.php?coverId={$saison->getPosterId()}' alt='{$nameSerie}'>
            <div class="text-info">
                <h3>$nameSerie</h3>
                <h3>$name</h3>
            </div>
        </div>
        <ul class="list-episode">
    <ul>
HTML
);
$episodes = $saison->getEpisodes();

foreach ($episodes as $episode) {
    $name = WebPage::escapeString($episode->getName());
    $numeroEp = $episode->getEpisodeNumber();
    $descEp = WebPage::escapeString($episode->getOverview());
    $webpage->appendContent("
      <li class='episode'><span>$numeroEp</span> - <span>$name</span>
      <p class='episode-desc'>$descEp</p>
  </li>
    ");
}

$webpage->appendContent(
    <<<HTML
        </ul>
</div>
HTML
);

echo $webpage->toHTML();