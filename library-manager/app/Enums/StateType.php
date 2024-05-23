<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum StateType: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;
    
    case SelfEmployed = 'SelfEmployed';
    case Sturgeon = 'Sturgeon';
    case JobPool = 'JobPool';
    case ExternalRecording = 'ExternalRecording';
}