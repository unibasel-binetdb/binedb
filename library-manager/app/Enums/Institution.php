<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Institution: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Ub = 'Ub';
    case Institution = 'Institution';
}