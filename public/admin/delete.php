<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\Tvshows;
use Exception\ParameterException;

try {
    if (isset($_GET['serieId'])) {
        if (ctype_digit($_GET['serieId'])) {
            $tvshow = Tvshows::findById(intval($_GET['serieId']));
            $tvshow->delete();
            header('Location: ../index.php');
        } else {
            throw new ParameterException();
        }
    } else {
        throw new ParameterException();
    }
} catch (ParameterException) {
    http_response_code(400);
} catch (EntityNotFoundException) {
    http_response_code(404);
} catch (Exception) {
    http_response_code(500);
}