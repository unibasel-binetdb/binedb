<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum SlsKey: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case YesUni = 'YesUni';
    case YesDirect = 'YesDirect';
    case No = 'No';
}