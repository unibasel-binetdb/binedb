<?php

namespace App\Util;

trait EnumEnhancements
{
    public static function values(): array
    {
        $reflection = new \ReflectionEnum(self::class);
        $cases = $reflection->getCases();

        $mapped = array();
        foreach($cases as $case) {
            $enumCase = $case->getValue();
            $mapped[$case->getName()] = $enumCase->translate();
        }

        return $mapped;
    }

    public static function translateWhenNotNull(): string {
        return 'blub';
    }
}