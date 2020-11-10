<?php


namespace app;


class PhotoProcessor
{
    private const DOG_LABEL_NAME = 'Dog';

    public static function checkIfDogIsPresent(array $labels): bool
    {
        foreach ($labels as $label) {
            if ($label['Name'] === self::DOG_LABEL_NAME) {
                return true;
            }
        }
        return false;
    }
}