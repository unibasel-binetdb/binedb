<?php

namespace App\Enums;

use App\Util\EnumEnhancements;
use Bjerke\Enum\HasTranslations;
use Bjerke\Enum\UsesTranslations;

enum Occupation: string implements HasTranslations
{
    use UsesTranslations;
    use EnumEnhancements;

    case ContactPerson = 'ContactPerson';
    case Trainee = 'Trainee';
    case LibraryStaff = 'LibraryStaff';
    case LibraryResponsible = 'LibraryResponsible';
    case LibraryManagement = 'LibraryManagement';
    case Cv = 'Cv';
    case Specialist = 'Specialist';
    case ManagingDirector = 'ManagingDirector';
    case Assistant = 'Assistant';
    case InstitutionLeadership = 'InstitutionLeadership';
    case OrganizationalSuperiors = 'OrganizationalSuperiors';
    case ProjectStaff = 'ProjectStaff';
    case SturgeonLibrarian = 'SturgeonLibrarian';
    case IzLibrarian = 'IzLibrarian';
}