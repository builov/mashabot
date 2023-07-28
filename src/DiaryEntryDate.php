<?php

namespace Builov\MashaBot;

class DiaryEntryDate
{
    public static array $textValues = [
        'Сегодня',
        'Вчера',
        'Позавчера',
        'Другая дата'
    ];

    public static int $keyboardColumns = 2;

    public static function getTextValues()
    {
        return self::$textValues;
    }

}