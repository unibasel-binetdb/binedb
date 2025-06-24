<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\Desk;
use App\Models\Library;
use App\Models\Location;
use App\Models\SignatureAssignment;
use App\Models\SignatureSpan;
use Generator;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class CollectionExport implements FromGenerator, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;

    private ?int $onlyLibraryId = NULL;
    private $onlyActive = NULL;
    private $usageUnit = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $this->usageUnit = $request->input('usage_unit');

        return $this;
    }
    public function forLibrary($libraryId): self
    {
        $this->onlyLibraryId = $libraryId;

        return $this;
    }

    public function generator(): Generator
    {
        $generated = [];
        $locQry = Location::query();
        $locQry = $locQry->join('libraries', 'locations.library_id', '=', 'libraries.id');

        if ($this->onlyLibraryId !== NULL)
            $locQry = $locQry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $locQry = $locQry->where('libraries.is_active', $this->onlyActive);

        if ($this->usageUnit !== NULL)
            $locQry = $locQry->where('usage_unit', $this->usageUnit);

        $locations = $locQry->get();

        foreach ($locations as $m) {
            $generated[] = [
                'type' => trans('location.singular'),
                'bibcode' => $m->library->bibcode,
                'lib_name' => $m->library->name,
                'lib_active' => $m->library->is_active,
                'code' => $m->code,
                'name' => $m->loc_name,
                'example_rule' => $m->example_rule,
                'usage_unit' => $m->usage_unit?->translate(),
                'comment' => $m->comment
            ];
        }

        $deskQry = Desk::query();
        $deskQry = $deskQry->join('libraries', 'desks.library_id', '=', 'libraries.id');

        if ($this->onlyLibraryId !== NULL)
            $deskQry = $deskQry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $deskQry = $deskQry->where('libraries.is_active', $this->onlyActive);

        $desks = $deskQry->get();

        foreach ($desks as $m) {
            $generated[] = [
                'type' => trans('desk.singular'),
                'bibcode' => $m->library->bibcode,
                'lib_name' => $m->library->name,
                'lib_active' => $m->library->is_active,
                'code' => $m->code,
                'name' => $m->name,
                'comment' => $m->comment
            ];
        }

        $spanQry = SignatureSpan::query();
        $spanQry = $spanQry->join('libraries', 'signature_spans.library_id', '=', 'libraries.id');

        if ($this->onlyLibraryId !== NULL)
            $spanQry = $spanQry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $spanQry = $spanQry->where('libraries.is_active', $this->onlyActive);

        $signatureSpans = $spanQry->get();

        foreach ($signatureSpans as $m) {
            $generated[] = [
                'type' => trans('library.signatureTitle') . ' ' . trans('signature.signatureSpan'),
                'bibcode' => $m->library->bibcode,
                'lib_name' => $m->library->name,
                'lib_active' => $m->library->is_active,
                'span' => $m->span,
                'comment' => $m->comment
            ];
        }

        $assignQry = SignatureAssignment::query();
        $assignQry = $assignQry->join('libraries', 'signature_assignments.library_id', '=', 'libraries.id');

        if ($this->onlyLibraryId !== NULL)
            $assignQry = $assignQry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $assignQry = $assignQry->where('libraries.is_active', $this->onlyActive);

        $signatureAssignments = $assignQry->get();

        foreach ($signatureAssignments as $m) {
            $generated[] = [
                'type' => trans('library.signatureTitle') . ' ' . trans('signature.signatureAssignment'),
                'bibcode' => $m->library->bibcode,
                'lib_name' => $m->library->name,
                'lib_active' => $m->library->is_active,
                'assignment' => $m->assignment?->translate(),
                'comment' => $m->comment
            ];
        }

        $libraryQuery = Library::query();
        if ($this->onlyLibraryId !== NULL)
            $libraryQuery = $libraryQuery->where('id', $this->onlyLibraryId);

        $libraries = $libraryQuery->get();

        foreach ($libraries as $m) {
            if ($this->isNullOrEmptyString($m->storage) && $this->isNullOrEmptyString($m->location_comment) && $m->sticker == NULL) {
                continue;
            }

            $generated[] = [
                'type' => 'Etiketten',
                'bibcode' => $m->bibcode,
                'lib_name' => $m->name,
                'lib_active' => $m->is_active,
                'storage' => $m->storage,
                'sticker' => $m->sticker?->translate(),
                'location_comment' => $m->location_comment
            ];
        }

        usort($generated, function ($a, $b) {
            return strcmp($a['bibcode'], $b['bibcode']);
        });

        foreach ($generated as $g)
            yield $g;
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('export.type'),
            trans('location.code'),
            trans('export.description'),
            trans('location.exampleRule'),
            trans('location.usageUnit'),
            trans('signature.span'),
            trans('signature.assignment'),
            trans('location.comment'),
            trans('export.locationComment'),
            trans('library.storage'),
            trans('library.sticker'),
        ];
    }

    public function map($r): array
    {
        return [
            array_key_exists('bibcode', $r) ? $r['bibcode'] : "",
            array_key_exists('lib_name', $r) ? $r['lib_name'] : "",
            array_key_exists('lib_active', $r) ? ($r['lib_active'] ? trans('general.yes') : trans('general.no')) : "",
            array_key_exists('type', $r) ? $r['type'] : "",
            array_key_exists('code', $r) ? $r['code'] : "",
            array_key_exists('name', $r) ? $r['name'] : "",
            array_key_exists('example_rule', $r) ? $r['example_rule'] : "",
            array_key_exists('usage_unit', $r) ? $r['usage_unit'] : "",
            array_key_exists('span', $r) ? $r['span'] : "",
            array_key_exists('assignment', $r) ? $r['assignment'] : "",
            array_key_exists('comment', $r) ? $r['comment'] : "",
            array_key_exists('location_comment', $r) ? $r['location_comment'] : "",
            array_key_exists('storage', $r) ? $r['storage'] : "",
            array_key_exists('sticker', $r) ? $r['sticker'] : ""
        ];
    }

    public function title(): string
    {
        return trans('export.collectionSheet');
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
        );
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->autoSizeColumns($event);
            },
        ];
    }

    private function isNullOrEmptyString($str)
    {
        return ($str === null || trim($str) === '');
    }
}