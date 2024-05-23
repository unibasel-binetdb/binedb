<?php

namespace Database\Seeders;

use App\Enums\AssociatedType;
use App\Enums\ContactTopic;
use App\Enums\Education;
use App\Enums\Faculty;
use App\Enums\Institution;
use App\Enums\IzUsageCost;
use App\Enums\Occupation;
use App\Enums\Provider;
use App\Enums\SlspAgreement;
use App\Enums\SlspCarrier;
use App\Enums\SlspContact;
use App\Enums\SlspCost;
use App\Enums\SlspState;
use App\Enums\StateType;
use App\Enums\SubjectIndexing;
use App\Enums\Training;
use App\Enums\YesNo;
use App\Enums\YesNoAlma;
use App\Models\Library;
use App\Models\LibraryBuilding;
use App\Models\LibraryCatalog;
use App\Models\LibraryFunction;
use App\Models\LibrarySlsp;
use App\Models\LibraryStock;
use Illuminate\Database\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    //stores the old and new primary keys
    private $libraryIdMapping = [];
    private $personIdMapping = [];
    private $functionIdMapping = [];
    private $seals = [];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'info@unibas.ch',
            'password' => Hash::make('TemporaryPassword!'),
        ]);

        $this->loadSeals();
        $sourceDb = \DB::connection('sourcedb');
        $this->insertLibraries($sourceDb);
        $this->command->newLine(1);

        $this->insertStocks($sourceDb);
        $this->command->newLine(1);

        $this->insertBuildings($sourceDb);
        $this->command->newLine(1);

        $this->insertCatalogs($sourceDb);
        $this->command->newLine(1);

        $this->insertSlsps($sourceDb);
        $this->command->newLine(1);

        $this->insertFunctions($sourceDb);
        $this->command->newLine(1);

        $this->insertLocations($sourceDb);
        $this->command->newLine(1);

        $this->addEmptyRelations($sourceDb);
        $this->command->newLine(1);

        $this->insertDesks($sourceDb);
        $this->command->newLine(1);

        $this->insertSignatures($sourceDb);
        $this->command->newLine(1);

        $this->insertNzFields($sourceDb);
        $this->command->newLine(1);

        $this->insertPersons($sourceDb);
        $this->command->newLine(1);

        $this->insertPersonFunctions($sourceDb);
        $this->command->newLine(1);

        $this->insertPersonContacts($sourceDb);
        $this->command->newLine(1);

        $this->command->line("All done!");
    }

    private function insertLibraries(Connection $dbConnection)
    {
        $this->command->line('Import data from Bibliothek to libaries table...');

        $sourceLibraries = $dbConnection->table('Bibliothek')
            ->leftJoin("Finanzen", "Bibliothek.bibid", "Finanzen.bibid")
            ->leftJoin("EDV_Infrastruktur", "Bibliothek.bibid", "EDV_Infrastruktur.bibid")
            ->leftJoin("Status", "Bibliothek.bibid", "Status.bibid")
            ->leftJoin("Katalogisierinfo", "Bibliothek.bibid", "Katalogisierinfo.bibid")
            ->select([
                "Bibliothek.*",
                "Finanzen.Uni_Kostenstelle",
                "Finanzen.UB_Kostenstelle",
                "Finanzen.Bemerkung as Finanzen_Bemerkung",
                "EDV_Infrastruktur.Provider",
                "EDV_Infrastruktur.Bemerkung as EDV_Bemerkung",
                "EDV_Infrastruktur.IP_Adresse",
                "Status.Verbundbibliothek",
                "Status.Typ as state_type",
                "Status.Verbund_seit as state_since",
                "Status.Verbund_bis  as state_until",
                "Status.Bemerkung as state_comment",
                "Katalogisierinfo.Bemerkung as catalog_comment"
            ])
            ->get();

        $counter = 0;
        $factory = \App\Models\Library::factory();

        foreach ($sourceLibraries as $srcLib) {
            $isActive = true;

            if ($this->isNullOrEmpty($srcLib->Aleph_Bibcode) || str_ends_with($srcLib->Aleph_Bibcode, '-X') || $srcLib->Aleph_Bibcode == "-")
                $isActive = false;

            $dstLib = $factory->create([
                'is_active' => $isActive,
                'name' => $this->isNullOrEmpty($srcLib->Name) ? NULL : $srcLib->Name,
                'name_addition' => $this->isNullOrEmpty($srcLib->Name_Zusatz) ? NULL : $srcLib->Name_Zusatz,
                'short_name' => $this->isNullOrEmpty($srcLib->Kurzname) ? NULL : $srcLib->Kurzname,
                'alternative_name' => $this->isNullOrEmpty($srcLib->AlternativName) ? NULL : $srcLib->AlternativName,
                'bibcode' => $this->isNullOrEmpty($srcLib->Aleph_Bibcode) ? NULL : $srcLib->Aleph_Bibcode,
                'existing_since' => $this->isNullOrEmpty($srcLib->existiert_seit) ? NULL : $srcLib->existiert_seit,
                'shipping_street' => $this->isNullOrEmpty($srcLib->Strasse) ? NULL : $srcLib->Strasse,
                'shipping_pobox' => $this->isNullOrEmpty($srcLib->Postfach) ? NULL : $srcLib->Postfach,
                'shipping_zip' => $this->isNullOrEmpty($srcLib->PLZ) ? NULL : $srcLib->PLZ,
                'shipping_location' => $this->isNullOrEmpty($srcLib->Ort) ? NULL : $srcLib->Ort,
                'different_billing_address' => false,
                'institution_url' => $this->isNullOrEmpty($srcLib->URL_Institution) ? NULL : $srcLib->URL_Institution,
                'library_url' => $this->isNullOrEmpty($srcLib->URL_Bibliothek) ? NULL : $srcLib->URL_Bibliothek,
                'associated_type' => $this->associatedTypeFromString($srcLib->zugehoerig),
                'uni_regulations' => $this->isNullOrEmpty($srcLib->Uniordnung) ? NULL : $srcLib->Uniordnung,
                'faculty' => $this->facultyFromString($srcLib->Fakultaet),
                'departement' => $this->isNullOrEmpty($srcLib->Departement) ? NULL : $srcLib->Departement,
                'bibstats_identification' => $this->isNullOrEmpty($srcLib->Kennzahl_CH_BibStatistik) ? NULL : $srcLib->Kennzahl_CH_BibStatistik,
                'library_comment' => $this->isNullOrEmpty($srcLib->Bemerkung) ? NULL : $srcLib->Bemerkung,
                'uni_costcenter' => $this->isNullOrEmpty($srcLib->Uni_Kostenstelle) ? NULL : $srcLib->Uni_Kostenstelle,
                'ub_costcenter' => $this->isNullOrEmpty($srcLib->UB_Kostenstelle) ? NULL : $srcLib->UB_Kostenstelle,
                'finance_comment' => $this->isNullOrEmpty($srcLib->Finanzen_Bemerkung) ? NULL : $srcLib->Finanzen_Bemerkung,
                'it_provider' => $this->isNullOrEmpty($srcLib->Provider) ? NULL : $this->providerFromString($srcLib->Provider),
                'it_comment' => $this->isNullOrEmpty($srcLib->EDV_Bemerkung) ? NULL : $srcLib->EDV_Bemerkung,
                'ip_address' => $this->isNullOrEmpty($srcLib->IP_Adresse) ? NULL : $srcLib->IP_Adresse,
                'iz_library' => $this->isNullOrEmpty($srcLib->Verbundbibliothek) ? false : strtolower($srcLib->Verbundbibliothek) == "ja",
                'state_type' => $this->isNullOrEmpty($srcLib->state_type) ? NULL : $this->stateTypeFromString($srcLib->state_type),
                'state_since' => $this->isNullOrEmpty($srcLib->state_since) ? NULL : $srcLib->state_since,
                'state_until' => $this->isNullOrEmpty($srcLib->state_until) ? NULL : $srcLib->state_until,
                'state_comment' => $this->isNullOrEmpty($srcLib->state_comment) ? NULL : $srcLib->state_comment,
                "sticker" => null,
                "colletion_comment" => $this->isNullOrEmpty($srcLib->catalog_comment) ? NULL : $srcLib->catalog_comment,
            ]);

            $counter++;
            $this->libraryIdMapping[$srcLib->bibid] = $dstLib->id;
        }

        $this->command->info('Inserted ' . $counter . ' libraries.');
    }

    private function addEmptyRelations(Connection $dbConnection) {
        $this->command->line('Check every library for missing sub entities and fill empty relations..');

        $libs = Library::all();
        $stockFactory = LibraryStock::factory();
        $buildingFactory = LibraryBuilding::factory();
        $slspFactory = LibrarySlsp::factory();
        $catalogFactory = LibraryCatalog::factory();
        $functionFactory = LibraryFunction::factory();
        
        $updates = 0;
        $libs->each(function($lib) use (&$stockFactory, &$buildingFactory, &$slspFactory, &$catalogFactory, &$functionFactory, &$updates)
        {
            $hasUpdate = false;

            if($lib->stock == null || !$lib->stock) {
                $stockFactory->create([
                    'library_id' => $lib->id
                ]);

                $hasUpdate = true;
            }

            if($lib->building == null || !$lib->building) {
                $buildingFactory->create([
                    'library_id' => $lib->id
                ]);

                $hasUpdate = true;
            }

            if($lib->slsp == null || !$lib->slsp) {
                $slspFactory->create([
                    'library_id' => $lib->id
                ]);

                $hasUpdate = true;
            }

            if($lib->catalog == null || !$lib->catalog) {
                $catalogFactory->create([
                    'library_id' => $lib->id
                ]);

                $hasUpdate = true;
            }

            if($lib->function == null || !$lib->function) {
                $functionFactory->create([
                    'library_id' => $lib->id
                ]);

                $hasUpdate = true;
            }

            if($hasUpdate)
                $updates = $updates + 1;
        });

        $this->command->info('Updated ' . $updates . ' libraries.');
    }

    private function insertStocks(Connection $dbConnection)
    {
        $this->command->line('Import data from Bestand to library_stocks table...');

        $sourceStocks = $dbConnection->table('Bestand')
            ->select([
                "Bestand.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\LibraryStock::factory();

        foreach ($sourceStocks as $srcStock) {

            $runningComment = '';
            if (!$this->isNullOrEmpty($srcStock->Bestand_Bemerkung))
                $runningComment .= $srcStock->Bestand_Bemerkung . '
';

            if (!$this->isNullOrEmpty($srcStock->Stand_Erfassung_Mono))
                $runningComment .= 'Stand Erfassung Mono: ' .$srcStock->Stand_Erfassung_Mono . '
';

            if (!$this->isNullOrEmpty($srcStock->Stand_Erfassung_Zss))
                $runningComment .= 'Stand Erfassung Zss: '. $srcStock->Stand_Erfassung_Zss . '
';

            $factory->create([
                'library_id' => $this->libraryIdMapping[$srcStock->bibid],
                'is_special_stock' => $srcStock->Besondere_Bestaende,
                'special_stock_comment' => $runningComment,
                'is_depositum' => $srcStock->Depositum_an_UB,
                'is_inst_depositum' => $srcStock->Depositum_an_Institut,
                'inst_depositum_comment' => $this->isNullOrEmpty($srcStock->Depositum_Bemerkung) ? NULL : $srcStock->Depositum_Bemerkung,
                'pushback' => $this->isNullOrEmpty($srcStock->Rueckschub) ? NULL : $srcStock->Rueckschub,
                'pushback_2010' => $this->isNullOrEmpty($srcStock->Rueckschub_bis_2010) ? NULL : $srcStock->Rueckschub_bis_2010,
                'pushback_2020' => $this->isNullOrEmpty($srcStock->Rueckschub_bis_2020) ? NULL : $srcStock->Rueckschub_bis_2020,
                'memory_library' => $this->isNullOrEmpty($srcStock->Speicherbibliothek) ? NULL : $srcStock->Speicherbibliothek,
                'running_1899' => $this->isNullOrEmpty($srcStock->lm_mono_1899) ? NULL : $srcStock->lm_mono_1899,
                'running_1999' => $this->isNullOrEmpty($srcStock->lm_mono_1900) ? NULL : $srcStock->lm_mono_1900,
                'running_2000' => $this->isNullOrEmpty($srcStock->lm_mono_2000) ? NULL : $srcStock->lm_mono_2000,
                'running_zss_1999' => $this->isNullOrEmpty($srcStock->lm_zss_1900) ? NULL : $srcStock->lm_zss_1900,
                'running_zss_2000' => $this->isNullOrEmpty($srcStock->lm_zss_2000) ? NULL : $srcStock->lm_zss_2000,
                'running_zss_1899' => $this->isNullOrEmpty($srcStock->lm_zss_1899) ? NULL : $srcStock->lm_zss_1899,
                'comment' => $this->isNullOrEmpty($srcStock->Bemerkung) ? NULL : $srcStock->Bemerkung,
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' stocks.');
    }

    private function insertBuildings(Connection $dbConnection)
    {
        $this->command->line('Import data from Gebaeude to library_buildings table...');

        $sourceBuildings = $dbConnection->table('Gebaeude')
            ->select([
                "Gebaeude.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\LibraryBuilding::factory();
        foreach ($sourceBuildings as $srcBuilding) {
            $factory->create([
                'library_id' => $this->libraryIdMapping[$srcBuilding->bibid],
                'copier' => $this->isNullOrEmpty($srcBuilding->Kopierer) ? NULL : $srcBuilding->Kopierer,
                'additional_devices' => $this->isNullOrEmpty($srcBuilding->Weitere_Geraete) ? NULL : $srcBuilding->Weitere_Geraete,
                'comment' => $this->isNullOrEmpty($srcBuilding->Bemerkung) ? NULL : $srcBuilding->Bemerkung,
                'key' => $this->isNullOrEmpty($srcBuilding->Schluessel) ? false : strtolower($srcBuilding->Schluessel) == "ja",
                'key_depot' => $this->isNullOrEmpty($srcBuilding->Schluessel_Depot) ? NULL : $srcBuilding->Schluessel_Depot,
                'key_comment' => $this->isNullOrEmpty($srcBuilding->Schluessel_Bemerkung) ? NULL : $srcBuilding->Schluessel_Bemerkung,
                'operating_area' => $this->isNullOrEmpty($srcBuilding->Betriebsflaeche) ? NULL : $srcBuilding->Betriebsflaeche,
                'audience_area' => $this->isNullOrEmpty($srcBuilding->Betriebsflaeche_Publikum) ? NULL : $srcBuilding->Betriebsflaeche_Publikum,
                'staff_workspaces' => $this->isNullOrEmpty($srcBuilding->Arbeitsplaetze_Personal) ? NULL : $srcBuilding->Arbeitsplaetze_Personal,
                'audience_workspaces' => $this->isNullOrEmpty($srcBuilding->Arbeitsplaetze_Publikum) ? NULL : $srcBuilding->Arbeitsplaetze_Publikum,
                'space_comment' => $this->isNullOrEmpty($srcBuilding->Flaeche_Bemerkung) ? NULL : $srcBuilding->Flaeche_Bemerkung
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' buildings.');
    }

    private function insertSlsps(Connection $dbConnection)
    {
        $this->command->line('Import data from SLSP to library_slsps table...');

        $sourceSlsps = $dbConnection->table('SLSP')
            ->select([
                "SLSP.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\LibrarySlsp::factory();

        foreach ($sourceSlsps as $srcSlsp) {
            $factory->create([
                'library_id' => $this->libraryIdMapping[$srcSlsp->bibid],
                'status' => $this->isNullOrEmpty($srcSlsp->SLSP_Status) ? NULL : $this->slspStateFromString($srcSlsp->SLSP_Status),
                'status_comment' => $this->isNullOrEmpty($srcSlsp->SLSP_Status_Bemerkung) ? NULL : $srcSlsp->SLSP_Status_Bemerkung,
                'cost' => null,
                'usage' => $this->isNullOrEmpty($srcSlsp->Kosten_IZ_Nutzung) ? NULL : $this->izUsageCostFromString($srcSlsp->Kosten_IZ_Nutzung),
                'cost_comment' => $this->isNullOrEmpty($srcSlsp->Kosten_Bemerkung) ? NULL : $srcSlsp->Kosten_Bemerkung,
                'agreement' => $this->isNullOrEmpty($srcSlsp->Vereinbarung) ? NULL : $this->slspAgreementFromString($srcSlsp->Vereinbarung),
                'agreement_comment' => $this->isNullOrEmpty($srcSlsp->Vereinbarung_Bemerkung) ? NULL : $srcSlsp->Vereinbarung_Bemerkung,
                'ftes' => $this->isNullOrEmpty($srcSlsp->FTEs_nur_Zahlen) ? NULL : $srcSlsp->FTEs_nur_Zahlen,
                'fte_comment' => $this->isNullOrEmpty($srcSlsp->FTEs_Bemerkung) ? NULL : $srcSlsp->FTEs_Bemerkung,
                'comment' => $this->isNullOrEmpty($srcSlsp->Bemerkungsfeld_SLSP) ? NULL : $srcSlsp->Bemerkungsfeld_SLSP
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' slsps.');
    }

    private function insertCatalogs(Connection $dbConnection)
    {
        $this->command->line('Import data from Sachkatalog to library_catalogs table...');

        $sourceCatalogs = $dbConnection->table('Sachkatalog')
            ->select([
                "Sachkatalog.*"
            ])->get();

        $groupedCatalogs = array();
        foreach ($sourceCatalogs as $srcCatalog)
            $groupedCatalogs[$srcCatalog->bibid][] = $srcCatalog;


        $counter = 0;
        $factory = \App\Models\LibraryCatalog::factory();
        foreach ($groupedCatalogs as $libId => $srcGroup) {

            $commentStr = "";

            usort($srcGroup, function($a, $b) {
                return strnatcasecmp($a->Typ, $b->Typ);
            });

            foreach ($srcGroup as $srcCatalog) {
                if (!$this->isNullOrEmpty($srcCatalog->Typ))
                    $commentStr .= $srcCatalog->Typ . ', ';

                if (!$this->isNullOrEmpty($srcCatalog->Indikator))
                    $commentStr .= $srcCatalog->Indikator . ', ';

                if (!$this->isNullOrEmpty($srcCatalog->Bezeichnung))
                    $commentStr .= $srcCatalog->Bezeichnung . ', ';

                if (!$this->isNullOrEmpty($srcCatalog->Aleph_Index))
                    $commentStr .= $srcCatalog->Aleph_Index . ', ';

                if (!$this->isNullOrEmpty($srcCatalog->Bemerkung))
                    $commentStr .= $srcCatalog->Bemerkung . ', ';
      
                $commentStr .= '
';
            }

            $factory->create([
                'library_id' => $this->libraryIdMapping[$libId],
                'comment' => $commentStr
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' catalogs.');
    }

    private function insertFunctions(Connection $dbConnection)
    {
        $this->command->line('Import data from Alephmodul to library_functions table...');

        $sourceFunction = $dbConnection->table('Alephmodul')
            ->select([
                "Alephmodul.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\LibraryFunction::factory();

        foreach ($sourceFunction as $srcFunction) {
            $factory->create([
                'library_id' => $this->libraryIdMapping[$srcFunction->bibid],
                'cataloging' => $this->isNullOrEmpty($srcFunction->F_Kat) ? NULL : $this->yesNoFromString($srcFunction->F_Kat),
                'cataloging_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_F_Kat) ? NULL : $srcFunction->Bemerkung_F_Kat,
                'subject_idx_local' => $this->isNullOrEmpty($srcFunction->S_Kat_690) ? NULL : $this->yesNoFromString($srcFunction->S_Kat_690),
                'subject_idx_gnd' => $this->isNullOrEmpty($srcFunction->S_Kat_Verbund) ? NULL : $this->subjectIndexingFromString($srcFunction->S_Kat_Verbund),
                'subject_idx_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_S_Kat) ? NULL : $srcFunction->Bemerkung_S_Kat,
                'acquisition' => $this->isNullOrEmpty($srcFunction->E_Kat) ? NULL : $this->yesNoFromString($srcFunction->E_Kat),
                'acquisition_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_E_Kat) ? NULL : $srcFunction->Bemerkung_E_Kat,
                'magazine_management' => $this->isNullOrEmpty($srcFunction->Zss_Verwaltung) ? NULL : $this->yesNoFromString($srcFunction->Zss_Verwaltung),
                'magazine_management_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_Zss) ? NULL : $srcFunction->Bemerkung_Zss,
                'lending' => $this->isNullOrEmpty($srcFunction->Ausleihe) ? NULL : $this->yesNoAlmaFromString($srcFunction->Ausleihe),
                'lending_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_Ausleihe) ? NULL : $srcFunction->Bemerkung_Ausleihe,
                'self_lending' => $this->isNullOrEmpty($srcFunction->Webselbstverbuchung) ? NULL : $this->yesNoFromString($srcFunction->Webselbstverbuchung),
                'self_lending_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_Webselbstverbuchung) ? NULL : $srcFunction->Bemerkung_Webselbstverbuchung,
                'basel_carrier' => null,
                'basel_carrier_comment' => null,
                'slsp_carrier' => null,
                'slsp_carrier_comment' => null,
                'rfid' => $this->isNullOrEmpty($srcFunction->RFID) ? NULL : $this->yesNoFromString($srcFunction->RFID),
                'rfid_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_RFID) ? NULL : $srcFunction->Bemerkung_RFID,
                'slsp_bursar' => $this->isNullOrEmpty($srcFunction->Clearing) ? NULL : $this->slspCarrierFromString($srcFunction->Clearing),
                'slsp_bursar_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung_Clearing) ? NULL : $srcFunction->Bemerkung_Clearing,
                'print_daemon' => null,
                'print_daemon_comment' => null
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' functions.');
    }

    private function insertLocations(Connection $dbConnection)
    {
        $this->command->line('Import data from AlephCollection to locations table...');

        $sourceLocations = $dbConnection->table('AlephCollection')
            ->leftJoin("Bibliothek", "AlephCollection.Aleph_Bibcode", "Bibliothek.Aleph_Bibcode")
            ->select([
                "AlephCollection.*",
                "Bibliothek.bibid as bibid"
            ])->get();

        $counter = 0;
        $factory = \App\Models\Location::factory();

        foreach ($sourceLocations as $srcLocation) {
            if ($this->isNullOrEmpty($srcLocation->bibid))
                continue;

            $factory->create([
                'library_id' => $this->libraryIdMapping[$srcLocation->bibid],
                'code' => $this->isNullOrEmpty($srcLocation->Zweigstelle_Code) ? null : $srcLocation->Zweigstelle_Code,
                'loc_name' => $this->isNullOrEmpty($srcLocation->Zweigstelle_Bezeichnung) ? null : $srcLocation->Zweigstelle_Bezeichnung,
                'example_rule' => $this->isNullOrEmpty($srcLocation->Exemplarstatus) ? null : $srcLocation->Exemplarstatus,
                'usage_unit' => null,
                'comment' => $this->isNullOrEmpty($srcLocation->Bemerkung) ? null : $srcLocation->Bemerkung
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' locations.');
    }

    private function insertDesks(Connection $dbConnection)
    {
        $this->command->line('Import data from AlephSublib to desks table...');

        $sourceDesks = $dbConnection->table('AlephSublib')
            ->leftJoin("Bibliothek", "AlephSublib.Aleph_Bibcode", "Bibliothek.Aleph_Bibcode")
            ->select([
                "AlephSublib.*",
                "Bibliothek.bibid as bibid"
            ])->get();

        $counter = 0;
        $factory = \App\Models\Desk::factory();

        foreach ($sourceDesks as $srcDesk) {
            if ($this->isNullOrEmpty($srcDesk->bibid))
                continue;

            $factory->create([
                'library_id' => $this->libraryIdMapping[$srcDesk->bibid],
                'comment' => $this->isNullOrEmpty($srcDesk->Bemerkung) ? null : $srcDesk->Bemerkung
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' desks.');
    }

    private function insertSignatures(Connection $dbConnection)
    {
        $this->command->line('Import data from Katalogisierinfo to signature_spans table...');

        $sourceSignatures = $dbConnection->table('Katalogisierinfo')
            ->select([
                "Katalogisierinfo.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\SignatureSpan::factory();

        foreach ($sourceSignatures as $srcSignature) {
            if (!$this->isNullOrEmpty($srcSignature->Signaturvorspann)) {
                $factory->create([
                    'library_id' => $this->libraryIdMapping[$srcSignature->bibid],
                    'span' => $srcSignature->Signaturvorspann,
                    'comment' => $this->isNullOrEmpty($srcSignature->Signatur_Bemerkung) ? NULL : $srcSignature->Signatur_Bemerkung,
                ]);
            }

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' signatures.');
    }

    private function insertNzFields(Connection $dbConnection)
    {
        $this->command->line('Import data from Lokalcode to libaries table...');

        $sourceCodes = $dbConnection->table('Lokalcode')
            ->select([
                "Lokalcode.*"
            ])->get();

        $groupedCodes = array();
        foreach ($sourceCodes as $srcCode)
            $groupedCodes[$srcCode->bibid][] = $srcCode;

        $counter = 0;

        foreach ($groupedCodes as $bibId => $codes) {
            $fields = [];

            foreach ($codes as $code) {
                $cmtStr = $code->Bemerkung;

                if (!$this->isNullOrEmpty($code->Indikator))
                    $cmtStr .= ', ' . $code->Indikator;

                $fields[] = [
                    'subfield' => $code->Unterfeld,
                    'name' => $code->Bezeichnung,
                    'code' => $code->Code,
                    'comment' => $cmtStr
                ];

                $counter++;
            }

            LibraryCatalog::where('library_id', $this->libraryIdMapping[$bibId])->update([
                'iz_fields' => $fields
            ]);
        }

        $this->command->info('Inserted ' . $counter . ' iz fields.');
    }

    private function insertPersons(Connection $dbConnection)
    {
        $this->command->line('Import data from Person to people table...');

        $sourcePersons = $dbConnection->table('Person')
            ->select([
                "Person.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\Person::factory();

        foreach ($sourcePersons as $srcPerson) {
            $seal = current(array_filter($this->seals, function ($e) use ($srcPerson) {
                if ($e['name'] == $srcPerson->Nachname . ', ' . $srcPerson->Vorname)
                    return true;

                return false;
            }));

            $dstPerson = $factory->create([
                'seal' => $seal == FALSE ? null : $seal["seal"],
                'last_name' => $this->isNullOrEmpty($srcPerson->Nachname) ? NULL : $srcPerson->Nachname,
                'first_name' => $this->isNullOrEmpty($srcPerson->Vorname) ? NULL : $srcPerson->Vorname,
                'gender' => $this->isNullOrEmpty($srcPerson->Anrede) ? NULL : $srcPerson->Anrede,
                'training' => $this->isNullOrEmpty($srcPerson->Schulung) ? NULL : $this->trainingFromString($srcPerson->Schulung),
                'training_cataloging' => $srcPerson->Schulung_Katalogisierung,
                'training_indexing' => $srcPerson->Schulung_Sacherschliessung,
                'training_acquisition' => $srcPerson->Schulung_Erwerbung,
                'training_magazine' => $srcPerson->Schulung_ZSV,
                'training_lending' => $srcPerson->Schulung_Ausleihe,
                'education' => $this->isNullOrEmpty($srcPerson->Ausbildung) ? NULL : $this->educationFromString($srcPerson->Ausbildung),
                'comment' => $this->isNullOrEmpty($srcPerson->Bemerkung) ? NULL : $srcPerson->Bemerkung
            ]);

            $counter++;
            $this->personIdMapping[$srcPerson->perid] = $dstPerson->id;
        }

        $this->command->info('Inserted ' . $counter . ' persons.');
    }

    private function insertPersonFunctions(Connection $dbConnection)
    {
        $this->command->line('Import data from Funktion to person_functions table...');

        $sourceFunctions = $dbConnection->table('Funktion')
            ->select([
                "Funktion.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\PersonFunction::factory();

        foreach ($sourceFunctions as $srcFunction) {
            $dstFunction = $factory->create([
                'person_id' => $this->personIdMapping[$srcFunction->perid],
                'library_id' => $this->libraryIdMapping[$srcFunction->bibid],
                'phone' => $this->isNullOrEmpty($srcFunction->Telefon) ? NULL : $srcFunction->Telefon,
                'email' => $this->isNullOrEmpty($srcFunction->Email) ? NULL : $srcFunction->Email,
                'work' => $this->isNullOrEmpty($srcFunction->Taetigkeit) ? NULL : $this->occupationFromString($srcFunction->Taetigkeit),
                'slsp_contact' => $this->isNullOrEmpty($srcFunction->SLSP_Ansprechpartner) ? NULL : $this->slspContactFromString($srcFunction->SLSP_Ansprechpartner),
                'percentage_of_employment' => $this->isNullOrEmpty($srcFunction->Stellenprozent) ? NULL : $srcFunction->Stellenprozent,
                'presence_times' => $this->isNullOrEmpty($srcFunction->Praesenzzeiten) ? NULL : $srcFunction->Praesenzzeiten,
                'work_start' => $this->isNullOrEmpty($srcFunction->seit_wann) ? NULL : $srcFunction->seit_wann,
                'work_end' => $this->isNullOrEmpty($srcFunction->bis_wann) ? NULL : $srcFunction->bis_wann,
                'exited' => $srcFunction->ausgetreten,
                'institution' => $this->isNullOrEmpty($srcFunction->zugehoerig) ? Institution::Ub : $this->institutionFromString($srcFunction->zugehoerig),
                'address_list' => $srcFunction->Adressliste,
                'email_list' => $srcFunction->Emailliste,
                'function_comment' => $this->isNullOrEmpty($srcFunction->Bemerkung) ? NULL : $srcFunction->Bemerkung
            ]);

            $counter++;
            $this->functionIdMapping[$srcFunction->funkid] = $dstFunction->id;
        }

        $this->command->info('Inserted ' . $counter . ' person functions.');
    }

    private function insertPersonContacts(Connection $dbConnection)
    {
        $this->command->line('Import data from Thema to Contact table...');

        $sourceTopics = $dbConnection->table('Thema')
            ->select([
                "Thema.*"
            ])->get();

        $counter = 0;
        $factory = \App\Models\Contact::factory();

        foreach ($sourceTopics as $srcTopic) {
            $topic = $this->contactTopicFromString($srcTopic->Thema);
            if ($topic == null)
                continue;

            $factory->create([
                'person_function_id' => $this->functionIdMapping[$srcTopic->funkid],
                'topic' => $topic,
                'comment' => $this->isNullOrEmpty($srcTopic->Bemerkung) ? NULL : $srcTopic->Bemerkung
            ]);

            $counter++;
        }

        $this->command->info('Inserted ' . $counter . ' contacts.');
    }


    ///ENUM HELPERS

    private function associatedTypeFromString($str): AssociatedType|null
    {
        switch ($str) {
            case "Uni":
                return AssociatedType::Uni;
            case "uniass":
                return AssociatedType::Uniass;
            case "uninah":
                return AssociatedType::Uninah;
            case "BS":
                return AssociatedType::Bs;
            case "BL":
                return AssociatedType::Bl;
            case "BS/BL":
                return AssociatedType::BsBl;
            case "HAN":
                return AssociatedType::Han;
            case "Privat":
                return AssociatedType::Privat;
            case "Spital":
                return AssociatedType::Hospital;
        }

        return null;
    }

    private function facultyFromString($str): Faculty|null
    {
        switch ($str) {
            case "Theologie":
                return Faculty::Theology;
            case "Phil.-Hist.":
                return Faculty::PhilHist;
            case "Phil.-Nat.":
                return Faculty::PhilNat;
            case "Medizin":
                return Faculty::Medicin;
            case "Rechtswissenschaften":
                return Faculty::LawSciences;
            case "Wirtschaftswissenschaften":
                return Faculty::Economics;
            case "Psychologie":
                return Faculty::Psychology;
            case "Dienstleistungsbereich":
                return Faculty::ServiceSector;
            case "Interdisziplinäre Einrichtung":
                return Faculty::InterdisciplinaryFacility;
        }

        return null;
    }

    private function providerFromString($str): Provider|null
    {
        switch ($str) {
            case "Uninetz":
                return Provider::Uninetz;
            case "Unispital":
                return Provider::Unispital;
            case "Danebs":
                return Provider::Danebs;
            case "???":
                return null;
            case "andere":
                return null;
        }

        $this->command->warn('Failed to find Provider enum value: ' . $str);

        return null;
    }

    private function yesNoFromString($str): YesNo|null
    {
        switch ($str) {
            case "ja":
                return YesNo::Yes;
            case "nein":
                return YesNo::No;
            case "Interesse":
                return null;
        }

        $this->command->warn('Failed to find YesNo enum value: ' . $str);

        return null;
    }

    private function yesNoAlmaFromString($str): YesNoAlma|null
    {
        switch ($str) {
            case "ja":
                return YesNoAlma::YesWith;
            case "nein":
                return YesNoAlma::No;
            case "Interesse":
                return null;
        }

        $this->command->warn('Failed to find YesNo enum value: ' . $str);

        return null;
    }

    private function trainingFromString($str): Training|null
    {
        switch ($str) {
            case "durch Verbund":
                return Training::BiNeBasel;
            case "durch UB":
                return Training::Ub;
            case "durch Institut":
                return Training::Institute;
            case "keine":
                return null;
            case "zu klären":
                return Training::ToClarify;
        }

        $this->command->warn('Failed to find Training enum value: ' . $str);

        return null;
    }

    private function educationFromString($str): Education|null
    {
        switch ($str) {
            case "Fachfrau/-mann I+D":
                return Education::IdSpecialist;
            case "BSc in Information Science (Chur/Genf)":
                return Education::BscInfoSciChGe;
            case "MSc in Information Science (Chur/Genf)":
                return Education::MscInfoSciChGe;
            case "MAS in Information Science (Chur)":
                return Education::MasInfoSciCh;
            case "MAS Bibliotheks- und Informationswissenschaften (WiBi) (Zürich)":
                return Education::MasBibInfoSciWiZu;
            case "MAS in Archival and Information Science (Bern)":
                return Education::MasArchInfoSciBe;
            case "CAS in Information und Dokumentation (Luzern)":
                return Education::CasInfoDocLu;
            case "Diplombibliothekar/in":
                return Education::GraduateLibrarian;
            case "andere bibliothekarische Ausbildung":
                return Education::OtherLibraryTraining;
            case "Buchhändler/in":
                return Education::Bookseller;
            case "in Ausbildung":
                return Education::InEducation;
            case "keine bibliothekarische Ausbildung":
                return Education::NoLibraryTraining;
            case "keine Angabe":
                return null;
        }

        $this->command->warn('Failed to find Education enum value: ' . $str);

        return null;
    }

    private function occupationFromString($str): Occupation|null
    {
        switch ($str) {
            case "Ansprechperson":
                return Occupation::ContactPerson;
            case "Auszubildende/r":
                return Occupation::Trainee;
            case "Bibliotheksmitarbeiter/in":
                return Occupation::LibraryStaff;
            case "Bibliotheksverantwortliche/r":
                return Occupation::LibraryResponsible;
            case "Bibliotheksverwaltung":
                return Occupation::LibraryManagement;
            case "CV":
                return Occupation::Cv;
            case "Fachreferat":
                return Occupation::Specialist;
            case "Geschäftsführer/in (Uni BS)":
                return Occupation::ManagingDirector;
            case "Hilfsassistent/in":
                return Occupation::Assistant;
            case "Institutionsleitung":
                return Occupation::InstitutionLeadership;
            case "Organisatorisch Vorgesetzte/r":
                return Occupation::OrganizationalSuperiors;
            case "Projektmitarbeiter/in":
                return Occupation::ProjectStaff;
            case "Störbibliothekar/in":
                return Occupation::SturgeonLibrarian;
            case "Verbundbibliothekar/in":
                return Occupation::IzLibrarian;
            case "?":
                return NULL;
        }

        $this->command->warn('Failed to find Occupation enum value: ' . $str);

        return null;
    }

    private function slspContactFromString($str): SlspContact|null
    {
        switch ($str) {
            case "leer":
                return null;
            case "Bibliothekarisches":
                return SlspContact::Librarianship;
            case "Vertrag":
                return SlspContact::Contract;
            case "Bibliothekarisches/Vertrag":
                return SlspContact::LibrarianshipAndContract;
        }

        $this->command->warn('Failed to find SlspContact enum value: ' . $str);

        return null;
    }

    private function institutionFromString($str): Institution|null
    {
        switch ($str) {
            case "UB":
                return Institution::Ub;
            case "Institution":
                return Institution::Institution;
        }

        $this->command->warn('Failed to find Institution enum value: ' . $str);

        return null;
    }

    private function contactTopicFromString($str): ContactTopic|null
    {
        switch ($str) {
            case "Aleph":
                return ContactTopic::A_Alma;
            case "Ausleihe Aleph":
                return ContactTopic::B_Borrow;
            case "Bibliothek":
                return ContactTopic::C_Library;
            case "CV":
                return ContactTopic::D_Cv;
            case "Ejournal":
                return null;
            case "Ejournal weitere":
                return null;
            case "Erwerbung Aleph":
                return ContactTopic::E_Acquisition;
            case "F-Kat Aleph":
                return ContactTopic::F_FormalCataloging;
            case "S-Kat":
                return ContactTopic::H_SubjectCataloging;
            case "Zss-Verwaltung Aleph":
                return ContactTopic::I_MagazineManagement;
            case "Open Access":
                return null;
            case "Forschungsunterstuetzung":
                return null;
        }

        $this->command->warn('Failed to find ContactTopic enum value: ' . $str);

        return null;
    }

    private function slspStateFromString($str): SlspState|null
    {
        switch ($str) {
            case "uni":
                return SlspState::Uni;
            case "uninah":
                return SlspState::Cooperation;
            case "affiliiert":
                return SlspState::Affiliated;
            case "nichtaffiliiert":
                return SlspState::OwnContract;
        }

        $this->command->warn('Failed to find SlspState enum value: ' . $str);

        return null;
    }

    private function slspAgreementFromString($str): SlspAgreement|null
    {
        switch ($str) {
            case "kein Vertrag":
                return null;
            case "mit SLSP":
                return SlspAgreement::Slsp;
            case "mit SLSP + UB":
                return SlspAgreement::SlspPlusUb;
            case "mit UB":
                return SlspAgreement::Ub;
        }

        $this->command->warn('Failed to find SlspAgreement enum value: ' . $str);

        return null;
    }

    private function slspCostFromString($str): SlspCost|null
    {
        //always null
        return null;
    }

    private function izUsageCostFromString($str): IzUsageCost|null
    {
        switch ($str) {
            case "ja":
                return IzUsageCost::Yes;
            case "nein":
                return IzUsageCost::No;
        }

        $this->command->warn('Failed to find IzUsageCost enum value: ' . $str);

        return null;
    }

    private function subjectIndexingFromString($str): SubjectIndexing|null
    {
        switch ($str) {
            case "ja":
                return SubjectIndexing::Yes;
            case "nein":
                return SubjectIndexing::No;
            case "nur 600":
                return SubjectIndexing::OnlySixhundred;
            case "Interesse":
                return null;
        }

        $this->command->warn('Failed to find SubjectIndexing enum value: ' . $str);

        return null;
    }

    private function slspCarrierFromString($str): SlspCarrier|null
    {
        switch ($str) {
            case "ja":
                return SlspCarrier::YesUni;
            case "nein":
                return SlspCarrier::No;
            case "Interesse":
                return null;
        }

        $this->command->warn('Failed to find SlspCarrier enum value: ' . $str);

        return null;
    }

    private function stateTypeFromString($str): StateType|null
    {
        switch ($str) {
            case "Selbständig":
                return StateType::SelfEmployed;
            case "Stör":
                return StateType::Sturgeon;
            case "Stellenpool":
                return StateType::JobPool;
            case "Fremderfassung":
                return StateType::ExternalRecording;
            case "Teilerfassung":
                return null;
            case "Unbekannt":
                return null;
        }

        $this->command->warn('Failed to find StateType enum value: ' . $str);

        return null;
    }



    //HTML PARSERS

    private function loadSeals()
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTMLFile('https://ub.unibas.ch/cgi-bin2/ibb/AlephSigel.pl');

        $xpath = new \DOMXPath($doc);
        $query = "//table[@border='1' and @cellspacing='0' and @cellpadding='2' and @width='100%' and @summary='']";

        $tables = $xpath->query($query);
        if ($tables->length > 0) {
            $table = $tables->item(0);

            foreach ($table->getElementsByTagName('tr') as $row) {
                $cols = $row->getElementsByTagName('td');

                $emailCol = $cols->item(4);
                if ($emailCol == null)
                    continue;

                if ($emailCol->nodeValue == "E-Mail")
                    continue;

                $this->seals[] = [
                    "seal" => utf8_decode($cols->item(0)->nodeValue),
                    "name" => utf8_decode($cols->item(1)->nodeValue),
                    "email" => str_replace(" ", "@", utf8_decode($emailCol->nodeValue))
                ];
            }
        }
    }

    private function isNullOrEmpty($input): bool
    {
        return ($input == NULL || !isset($input) || trim($input) === '');
    }
}