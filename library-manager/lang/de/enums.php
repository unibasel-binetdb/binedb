<?php
use App\Enums\AssociatedType;
use App\Enums\CatalogingLevel;
use App\Enums\ContactTopic;
use App\Enums\Education;
use App\Enums\Faculty;
use App\Enums\Institution;
use App\Enums\IzUsageCost;
use App\Enums\Occupation;
use App\Enums\PrintDaemon;
use App\Enums\Provider;
use App\Enums\SignatureAssignmentType;
use App\Enums\SlspAgreement;
use App\Enums\SlspCarrier;
use App\Enums\SlspContact;
use App\Enums\SlspCost;
use App\Enums\SlspState;
use App\Enums\StateType;
use App\Enums\Sticker;
use App\Enums\SubjectIndexing;
use App\Enums\Training;
use App\Enums\UsageUnit;
use App\Enums\YesNo;
use App\Enums\YesNoAlma;

return [
    'training' => [
        Training::BiNeBasel->value => 'durch BiNeBasel',
        Training::Ub->value => 'durch UB',
        Training::Institute->value => 'durch Institut',
        Training::ToClarify->value => 'zu klären'
    ],
    'education' => [
        Education::IdSpecialist->value => 'Fachfrau/-mann I+D',
        Education::BscInfoSciChGe->value => 'BSc in Information Science (Chur/Genf)',
        Education::MscInfoSciChGe->value => 'MSc in Information Science (Chur/Genf)',
        Education::MasInfoSciCh->value => 'MAS in Information Science (Chur)',
        Education::MasBibInfoSciWiZu->value => 'MAS Bibliotheks- und Informationswissenschaften (WiBi) (Zürich)',
        Education::MasArchInfoSciBe->value => 'MAS in Archival and Information Science (Bern)',
        Education::CasInfoDocLu->value => 'CAS in Information und Dokumentation (Luzern)',
        Education::GraduateLibrarian->value => 'Diplombibliothekar/in',
        Education::OtherLibraryTraining->value => 'andere bibliothekarische Ausbildung',
        Education::Bookseller->value => 'Buchhändler/in',
        Education::InEducation->value => 'in Ausbildung',
        Education::NoLibraryTraining->value => 'keine bibliothekarische Ausbildung'
    ],
    'occupation' => [
        Occupation::ContactPerson->value => 'Ansprechperson',
        Occupation::Trainee->value => 'Auszubildende/r',
        Occupation::LibraryStaff->value => 'Bibliotheksmitarbeiter/in',
        Occupation::LibraryResponsible->value => 'Bibliotheksverantwortliche/r',
        Occupation::LibraryManagement->value => 'Bibliotheksverwaltung',
        Occupation::Cv->value => 'CV',
        Occupation::Specialist->value => 'Fachreferat',
        Occupation::ManagingDirector->value => 'Geschäftsführer/in (Uni BS)',
        Occupation::Assistant->value => 'Hilfsassistent/in',
        Occupation::InstitutionLeadership->value => 'Institutionsleitung',
        Occupation::OrganizationalSuperiors->value => 'Organisatorisch Vorgesetzte/r',
        Occupation::ProjectStaff->value => 'Projektmitarbeiter/in',
        Occupation::SturgeonLibrarian->value => 'Störbibliothekar/in',
        Occupation::IzLibrarian->value => 'IZ-Bibliothekar/in'
    ],
    'cataloging_level' => [
        CatalogingLevel::Zero->value => '00',
        CatalogingLevel::Twenty->value => '20',
        CatalogingLevel::Fifty->value => '50',
        CatalogingLevel::Ninety->value => '90'
    ],
    'associated_type' => [
        AssociatedType::Uni->value => 'Uni',
        AssociatedType::Uniass->value => 'uniass',
        AssociatedType::Uninah->value => 'uninah',
        AssociatedType::Bs->value => 'BS',
        AssociatedType::Bl->value => 'BL',
        AssociatedType::BsBl->value => 'BS/BL',
        AssociatedType::Han->value => 'HAN',
        AssociatedType::Privat->value => 'Privat',
        AssociatedType::Hospital->value => 'Spital'
    ],
    'slsp_contact' => [
        SlspContact::Librarianship->value => 'Bibliothekarisches',
        SlspContact::Contract->value => 'Vertrag',
        SlspContact::LibrarianshipAndContract->value => 'Bibliothekarisches/Vertrag',
    ],
    'slsp_state' => [
        SlspState::Uni->value => 'uni',
        SlspState::Cooperation->value => 'Kooperation',
        SlspState::Affiliated->value => 'affiliiert',
        SlspState::OwnContract->value => 'eigener Vertrag',
    ],
    'slsp_cost' => [
        SlspCost::Direct->value => 'Direkt an SLSP',
        SlspCost::BillingFte->value => 'Weiterverrechnung FTE & Alma-Logins',
        SlspCost::NoBillingFte->value => 'Keine Weiterverrechnung FTE & Alma-Logins',
    ],
    'slsp_agreement' => [
        SlspAgreement::Slsp->value => 'mit SLSP',
        SlspAgreement::SlspPlusUb->value => 'mit SLSP + UB',
        SlspAgreement::Ub->value => 'mit UB',
    ],
    'iz_usage_cost' => [
        IzUsageCost::Yes->value => 'Ja',
        IzUsageCost::No->value => 'Nein',
        IzUsageCost::OverInference->value => 'Über Störeinsatz',
    ],
    'contact_topic' => [
        ContactTopic::A_Alma->value => 'Alma',
        ContactTopic::B_Borrow->value => 'Ausleihe Alma',
        ContactTopic::C_Library->value => 'Bibliothek',
        ContactTopic::D_Cv->value => 'CV',
        ContactTopic::E_Acquisition->value => 'Erwerbung Alma',
        ContactTopic::F_FormalCataloging->value => 'Formalkatalogisierung Alma',
        ContactTopic::G_ObjectCataloging->value => 'Sacherschliessung lokal',
        ContactTopic::H_SubjectCataloging->value => 'Sacherschliessung GND',
        ContactTopic::I_MagazineManagement->value => 'Zeitschriftenverwaltung Alma'
    ],
    'usage_unit' => [
        UsageUnit::IZLocalClosed->value => 'IZ Local Closed Stacks',
        UsageUnit::IZLocalOpen->value => 'IZ Local Open Stacks',
        UsageUnit::IZGlobalClosed->value => 'IZ Global Closed Stacks',
        UsageUnit::IzGlobalOpen->value => 'IZ Global Open Stacks',
        UsageUnit::IZLibraryClosed->value => 'IZ Library Closed',
    ],
    'institution' => [
        Institution::Ub->value => 'UB',
        Institution::Institution->value => 'Institution'
    ],
    'faculty' => [
        Faculty::Theology->value => 'Theologie',
        Faculty::PhilHist->value => 'Phil.-Hist.',
        Faculty::PhilNat->value => 'Phil.-Nat.',
        Faculty::Medicin->value => 'Medizin',
        Faculty::LawSciences->value => 'Rechtswissenschaften',
        Faculty::Economics->value => 'Wirtschaftswissenschaften',
        Faculty::Psychology->value => 'Psychologie',
        Faculty::ServiceSector->value => 'Dienstleistungsbereich',
        Faculty::InterdisciplinaryFacility->value => 'Interdisziplinäre Einrichtung'
    ],
    'provider' => [
        Provider::Uninetz->value => 'Uninetz',
        Provider::Unispital->value => 'Unispital',
        Provider::Danebs->value => 'Danebs'
    ],
    'yes_no' => [
        YesNo::Yes->value => 'Ja',
        YesNo::No->value => 'Nein'
    ],
    'yes_no_alma' => [
        YesNoAlma::YesWith->value => 'Ja, mit Alma',
        YesNoAlma::YesWithout->value => 'Ja, ohne Alma',
        YesNoAlma::No->value => 'Nein'
    ],
    'slsp_carrier' => [
        SlspCarrier::YesUni->value => 'Ja, über Uni',
        SlspCarrier::YesDirect->value => 'Ja, direkt',
        SlspCarrier::No->value => 'Nein'
    ],
    'print_daemon' => [
        PrintDaemon::Daemon->value => 'Druckdaemon',
        PrintDaemon::Email->value => 'E-Mail',
        PrintDaemon::Queued->value => 'Druckwarteschlange'
    ],
    'subject_indexing' => [
        SubjectIndexing::Yes->value => 'Ja',
        SubjectIndexing::No->value => 'Nein',
        SubjectIndexing::OnlySixhundred->value => 'nur 600',
    ],
    'state_type' => [
        StateType::SelfEmployed->value => 'Selbständig',
        StateType::Sturgeon->value => 'Stör',
        StateType::JobPool->value => 'Stellenpool',
        StateType::ExternalRecording->value => 'Fremderfassung'
    ],
    'sticker' => [
        Sticker::BiNe->value => 'über BiNe',
        Sticker::Manual->value => 'in Bibliothek manuell',
        Sticker::Libstick->value => 'in Bibliothek mit Libstick'
    ],
    'signature_assignment_type' => [
        SignatureAssignmentType::AlmaCreated->value => 'In Alma Zugangsnummer erzeugen',
        SignatureAssignmentType::Manual->value => 'Manuelle Vergabe',
        SignatureAssignmentType::ExternalCounter->value => 'Externer RVK-Signatur-Zähler'
    ]
];