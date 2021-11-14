<?php

namespace App\Helpers\UrlShorter;

class FirstFiveLettersUrlShorter implements UrlShorter
{
    function getShorted($url, $optionalData = null): string
    {
        return substr($url, 0, 5);
    }

    function canRetry(): bool
    {
        return false;
    }
}
