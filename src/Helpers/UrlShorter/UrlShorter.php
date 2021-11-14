<?php

namespace App\Helpers\UrlShorter;

interface UrlShorter
{
    function getShorted($url, $optionalData = null): string;
    function canRetry(): bool;
}

abstract class UrlShortAlgorythm
{
    const FirstFiveLetters = 0;
    const RandomMD5 = 1;

    static function getList(): array
    {
        $reflector = new \ReflectionClass(UrlShortAlgorythm::class);
        return $reflector->getConstants();
    }
}

abstract class UrlShorterFactory
{
    static function getShorter($type): UrlShorter
    {
        switch ($type) {
            case UrlShortAlgorythm::RandomMD5:
                return new RandomMD5Shorter();
            case UrlShortAlgorythm::FirstFiveLetters:
            default:
                return new FirstFiveLettersUrlShorter();
        }
    }
}
