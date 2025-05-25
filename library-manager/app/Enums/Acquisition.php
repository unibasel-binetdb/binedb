<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Acquisition: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case YesWithDigirech = 'YesDigirech';
    case YesWithoutDigirech = 'Yes';
    case No = 'No';
}