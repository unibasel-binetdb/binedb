<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Provider: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Uninetz = 'Uninetz';
    case Unispital = 'Unispital';
    case Danebs = 'Danebs';
}