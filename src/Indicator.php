<?php

namespace Builov\MashaBot;

class Indicator
{
    public static array $list = [
        4 => [
            'sleep_duration',  //продолжительность сна
            ],
        5 => 'sleep_depth',     //глубина сна
        6 => 'morning_feeling', //самочувствие с утра
        7 => 'discomfort',      //неприятные ощущения в теле в течение дня
        8 => 'activity',        //акивность в течение дня
        9 => 'attention',       //концентрация внимания
        10 => 'mood_swings',    //перепады настроения в течение дня
        11 => 'tearfulness',    //плаксивость в течение дня
        12 => 'irritability'    //раздражительность в течение дня
    ];
}