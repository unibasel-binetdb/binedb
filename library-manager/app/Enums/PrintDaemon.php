<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum PrintDaemon: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case Daemon = 'Daemon';
    case Email = 'Email';
    case Queued = 'Queued';
}