<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum SlspAgreement: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Slsp = 'Slsp';
    case SlspPlusUb = 'SlspPlusUb';
    case Ub = 'Ub';
}