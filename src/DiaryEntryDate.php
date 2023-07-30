<?php

namespace Builov\MashaBot;

use Exception;

class DiaryEntryDate
{
    private static array $textValues = [
        'Сегодня',
        'Вчера',
        'Позавчера',
        '3 дня назад'
    ];

    public static int $keyboardColumns = 2;

    public static function getTextValues(): array
    {
        return self::$textValues;
    }

    /**
     * @throws Exception
     */
    public static function getDateFormatted($text): string
    {
        return match ($text) {
            self::$textValues[0] => date("Y-m-d"),
            self::$textValues[1] => date("Y-m-d", 'yesterday'),
            self::$textValues[2] => date("Y-m-d", "2 days ago"),
            self::$textValues[3] => date("Y-m-d", "3 days ago"),
            default => throw new Exception("Дата не определена"),
        };
    }
}