<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum SlspContact: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Librarianship = 'Librarianship';
    case Contract = 'Contract';
    case LibrarianshipAndContract = 'LibrarianshipAndContract';
}