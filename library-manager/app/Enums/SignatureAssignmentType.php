<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum SignatureAssignmentType: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case AlmaCreated = 'AlmaCreated';
    case Manual = 'Manual';
    case ExternalCounter = 'ExternalCounter';
}