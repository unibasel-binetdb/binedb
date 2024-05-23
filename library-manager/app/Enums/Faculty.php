<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Faculty: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Theology = 'Theology';
    case PhilHist = 'PhilHist';
    case PhilNat = 'PhilNat';
    case Medicin = 'Medicin';
    case LawSciences = 'LawSciences';
    case Economics = 'Economics';
    case Psychology = 'Psychology';
    case ServiceSector = 'ServiceSector';
    case InterdisciplinaryFacility = 'InterdisciplinaryFacility';
}