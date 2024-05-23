<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Sticker: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case BiNe = 'BiNe';
    case Manual = 'Manual';
    case Libstick = 'Libstick';
}