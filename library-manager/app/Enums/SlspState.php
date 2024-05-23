<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum SlspState: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Uni = 'Uni';
    case Cooperation = 'Cooperation';
    case Affiliated = 'Affiliated';
    case OwnContract = 'OwnContract';
}