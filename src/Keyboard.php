<?php

namespace Builov\MashaBot;

class Keyboard
{
    public function getDates(): array
    {
        return array_chunk(DiaryEntryDate::$textValues, DiaryEntryDate::$keyboardColumns, false);
    }

    public function getMoods(): array
    {
        return array_chunk(Mood::$values, Mood::$keyboardColumns, false);
    }

}