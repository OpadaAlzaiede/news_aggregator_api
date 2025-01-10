<?php

namespace App\Enums;

enum PreferenceForEnum: int
{
    case TITLE = 1;
    case AUTHOR = 2;
    case CATEGORY = 3;
    case SOURCE = 4;
}
