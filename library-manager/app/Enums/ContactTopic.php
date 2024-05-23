<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum ContactTopic: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case A_Alma = 'A_Alma';
    case B_Borrow = 'B_Borrow';
    case C_Library = 'C_Library';
    case D_Cv = 'D_Cv';
    case E_Acquisition = 'E_Acquisition';
    case F_FormalCataloging = 'F_FormalCataloging';
    case G_ObjectCataloging = 'G_ObjectCataloging';
    case H_SubjectCataloging = 'H_SubjectCataloging';
    case I_MagazineManagement = 'I_MagazineManagement';
}