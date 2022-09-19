<?php

declare(strict_types=1);

use Entity\Poster;
use Entity\Exception\EntityNotFoundException;
use Exception\ParameterException;

if (!isset($_GET['coverId']) || !ctype_digit($_GET['coverId'])) {
    $file = 'default.png';
    header('Content-Type: image/png');
    header('Content-Length: ' . filesize($file));
    echo file_get_contents($file);
}

try {
    $file = 'default.png';
    $cover = Poster::findById(intval($_GET['coverId']));
    if ($cover->getJpeg() === "") {
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($file));
        echo file_get_contents($file);
    } else {
        echo $cover->getJpeg();
    }
    echo $cover->getJpeg();
} catch (ParameterException) {
    echo "ParameterException";
    http_response_code(400);
} catch (EntityNotFoundException) {
    echo "EntityNotFoundException";
    http_response_code(404);
} catch (Exception) {
    echo "Exception";
    http_response_code(500);
}