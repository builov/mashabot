<?php

namespace Builov\MashaBot;

class Keyboard
{
    public function getDates(): array
    {
        return array_chunk(DiaryEntryDate::getTextValues(), DiaryEntryDate::$keyboardColumns, false);
    }

    public function getMoods(): array
    {
        return array_chunk(Mood::$values, Mood::$keyboardColumns, false);
    }

}