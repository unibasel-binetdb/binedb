<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Training: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case BiNeBasel = 'BiNeBasel';
    case Ub = 'Ub';
    case Institute = 'Institute';
    case ToClarify = 'ToClarify';
}