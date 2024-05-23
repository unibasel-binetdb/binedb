<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum SlspCost: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Direct = 'Direct';
    case BillingFte = 'BillingFte';
    case NoBillingFte = 'NoBillingFte';
}