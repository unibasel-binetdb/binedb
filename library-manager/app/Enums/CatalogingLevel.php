<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum CatalogingLevel: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Zero = 'Zero';
    case Twenty = 'Twenty';
    case Fifty = 'Fifty';
    case Ninety = 'Ninety';
}