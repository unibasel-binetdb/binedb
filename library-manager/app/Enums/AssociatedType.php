<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum AssociatedType: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Uni = 'Uni';
    case Uniass = 'Uniass';
    case Uninah = 'Uninah';
    case Bs = 'Bs';
    case Bl = 'Bl';
    case BsBl = 'BsBl';
    case Han = 'Han';
    case Privat = 'Privat';
    case Hospital = 'Hospital';
}