<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum UsageUnit: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case IZLocalClosed = 'IZLocalClosed';
    case IZLocalOpen = 'IZLocalOpen';
    case IZGlobalClosed = 'IZGlobalClosed';
    case IzGlobalOpen = 'IzGlobalOpen';
    case IZLibraryClosed = 'IZLibraryClosed';
}