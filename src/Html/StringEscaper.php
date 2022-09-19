<?php

namespace Html;

trait StringEscaper
{
    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web.
     * @param ?string $string La chaîne à protéger
     * @return string La chaîne protégée
     */
    public static function escapeString(?string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }

    public static function stripTagsAndTrim(?string $string): ?string
    {
        if ($string === null) {
            return null;
        }
        return strip_tags(trim($string));
    }
}
