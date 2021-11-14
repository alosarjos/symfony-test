<?php

namespace App\Helpers\UrlShorter;

class RandomMD5Shorter implements UrlShorter
{
    function getShorted($url, $optionalData = null): string
    {
        return substr(md5(uniqid(rand(), true)), 0, 5);
    }

    function canRetry(): bool
    {
        return true;
    }
}
