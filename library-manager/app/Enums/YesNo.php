<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum YesNo: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;
    
    case Yes = 'Yes';
    case No = 'No';
}