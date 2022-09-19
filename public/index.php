<?php

declare(strict_types=1);

use Entity\Collection\GenreCollection;
use Entity\Collection\TvshowsCollection;
use Html\AppWebPage;
use Html\WebPage;

$webpage = new AppWebPage();

$webpage->setTitle("NetFlop");
$webpage->appendCssUrl("css/style.css");
$webpage->appendCssUrl("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css");


$webpage->appendContent(
    <<<HTML
    <header class="header">
        <h1>Séries Tv</h1>
    </header>
    <main>
        <div class="menu">
            <form method="get">
                <select name="genre" id="genre">
                    <option value="">Trier par genres</option>
HTML);

$genres = GenreCollection::findAll();
foreach ($genres as $genre) {
    $nameG = $genre->getName();
    $idG = $genre->getId();
    $webpage->appendContent(
        <<<HTML
            <option value="$idG">$nameG</option>
HTML
);

}

$webpage->appendContent(
    <<<HTML
                </select>
                <button type="submit">Trier</button>
            </form>
            <a href="/admin/tvshows-form.php" class="ajout">Ajouter</a>  
        </div>
        <div class="main-wrapper">
    
HTML
);
$shows = null;
if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $shows = TvshowsCollection::findByGenreId(intval($_GET['genre']));
} else {
    $shows = TvshowsCollection::findAll();
}

foreach ($shows as $show) {
    $name = WebPage::escapeString($show->getName());
    $id = $show->getId();
    $resume = substr(WebPage::escapeString($show->getText()), 0, 200) . "...";
    $webpage->appendContent("
        <div class='box'>
                    <img src='poster.php?coverId={$show->getPosterId()}'>
                    <div class='overlay'>
                        <div class='text'>
                            <h3><a href='season.php?seriesId=$id'>$name</a></h3>
                            <p>$resume</p>
                        </div>
                        <ul class='tools'>
                            <li id='editBtn'>
                                <a href='/admin/tvshows-form.php?serieId={$show->getId()}'><i class='fa fa-edit'></i></a>
                            </li>
                            <li><a href='/admin/delete.php?serieId={$show->getId()}'><i class='fa fa-trash-o'></i></a></li>
                        </ul>
                    </div>
                </div>
    ");
}

$webpage->appendContent(
    <<<HTML
    </div>
HTML
);

echo $webpage->toHTML();