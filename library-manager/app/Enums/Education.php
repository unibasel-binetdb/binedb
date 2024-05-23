<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Education: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case IdSpecialist = 'IdSpecialist';
    case BscInfoSciChGe = 'BscInfoSciChGe';
    case MscInfoSciChGe = 'MscInfoSciChGe';
    case MasInfoSciCh = 'MasInfoSciCh';
    case MasBibInfoSciWiZu = 'MasBibInfoSciWiZu';
    case MasArchInfoSciBe = 'MasArchInfoSciBe';
    case CasInfoDocLu = 'CasInfoDocLu';
    case GraduateLibrarian = 'GraduateLibrarian';
    case OtherLibraryTraining = 'OtherLibraryTraining';
    case Bookseller = 'Bookseller';
    case InEducation = 'InEducation';
    case NoLibraryTraining = 'NoLibraryTraining';
}