<?php

if (! function_exists('getInitialPrenoms')) {
    /**
     * Retourne les initiales d'une chaîne de prénoms, sans séparateur.
     *
     * @param  string  $prenoms
     * @return string
     */
    function getInitialPrenoms(string $prenoms): string
    {
        $prenoms = trim($prenoms);
        if ($prenoms === '') {
            return '';
        }

        // Découpe par espaces multiples
        $parts = preg_split('/\s+/', $prenoms, -1, PREG_SPLIT_NO_EMPTY);

        // Récupère la première lettre de chaque mot, en majuscule multibyte
        $initials = array_map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)), $parts);

        // Concatène sans séparateur
        return implode('', $initials);
    }
}
