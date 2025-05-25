<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Digitization: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case WithAlma = 'WithAlma';
    case WithMyBib = 'WithMyBib';
}