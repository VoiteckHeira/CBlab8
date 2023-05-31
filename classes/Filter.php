<?php

class Filter
{

    // Funkcja generyczna
    private static function preventXSS($data)
    {
        // Usunięcie potencjalnie niebezpiecznych znaczników HTML i JavaScript
        $data = strip_tags($data);
        $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
        $data = preg_replace('/<\s*\/?script\s*>/i', '', $data);
        return $data;
    }
    public static function sanitizeData($data, $filterType)
    {
        $filter = FILTER_DEFAULT;
        switch ($filterType) {
            case 'str':
                $filter = FILTER_SANITIZE_STRING;
                break;
            case 'num':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                break;
            case 'email':
                $filter = FILTER_SANITIZE_EMAIL;
                break;
            case 'def':
                break;
        }

        $data = filter_var($data, $filter);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        $data = Filter::preventXSS($data); // Dodatkowe zabezpieczenie przed atakami XSS
        return $data;
    }
}

?>