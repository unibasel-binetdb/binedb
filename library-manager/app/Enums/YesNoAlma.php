<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum YesNoAlma: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case YesWith = 'YesWith';
    case YesWithout = 'YesWithout';
    case No = 'No';
}