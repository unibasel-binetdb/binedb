<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\LibraryCatalog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class CatalogExport implements FromQuery, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;

    private ?int $onlyLibraryId = NULL;
    private $onlyActive = NULL;
    private $only072 = NULL;
    private $only082 = NULL;
    private $only084 = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $only072 = $request->input('is_072');
        if ($only072 !== NULL)
            $this->only072 = $only072 == "1";

        $only082 = $request->input('is_082');
        if ($only082 !== NULL)
            $this->only082 = $only082 == "1";

        $only084 = $request->input('is_084');
        if ($only084 !== NULL)
            $this->only084 = $only084 == "1";

        return $this;
    }

    public function forLibrary($libraryId): self {
        $this->onlyLibraryId = $libraryId;

        return $this;
    }

    public function query()
    {
        $qry = LibraryCatalog::query();
        $qry->join('libraries', 'library_catalogs.library_id', '=', 'libraries.id');

         if($this->onlyLibraryId !== NULL)
            $qry->where('libraries.id', $this->onlyLibraryId);
        
        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);

        if ($this->only072 !== NULL)
            $qry->where('is_072', $this->only072);

        if ($this->only082 !== NULL)
            $qry->where('is_082', $this->only082);

        if ($this->only084 !== NULL)
            $qry->where('is_084', $this->only084);

        return $qry->orderBy('libraries.bibcode', 'asc');
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('libraryCatalog.is072'),
            trans('libraryCatalog.is082'),
            trans('libraryCatalog.is084'),
            trans('libraryCatalog.fields.comment'),
            'Feld Typ',
            trans('libraryCatalog.fields.field'),
            trans('libraryCatalog.fields.subfield'),
            trans('libraryCatalog.fields.code'),
            trans('libraryCatalog.fields.subfieldOrigin'),
            trans('libraryCatalog.fields.name'),
            trans('libraryCatalog.fields.comment')
        ];
    }

    public function map($catalog): array
    {
        $mapped = array();

        if ($catalog->nz_fields != NULL) {
            foreach ($catalog->nz_fields as $nzField) {
                $entry = [
                    $catalog->library->bibcode,
                    $catalog->library->name,
                    $catalog->library->is_active ? trans('general.yes') : trans('general.no'),
                    "",
                    "",
                    "",
                    "",
                    'NZ',
                    array_key_exists('field', $nzField) ? $nzField['field'] : "",
                    array_key_exists('subfield', $nzField) ? $nzField['subfield'] : "",
                    array_key_exists('code', $nzField) ? $nzField['code'] : "",
                    array_key_exists('subfieldOrigin', $nzField) ? $nzField['subfieldOrigin'] : "",
                    array_key_exists('name', $nzField) ? $nzField['name'] : "",
                    array_key_exists('comment', $nzField) ? $nzField['comment'] : ""
                ];

                $mapped[] = $entry;
            }
        }

        if ($catalog->iz_fields != NULL) {
            foreach ($catalog->iz_fields as $izField) {
                $entry = [
                    $catalog->library->bibcode,
                    $catalog->library->name,
                    $catalog->library->is_active ? trans('general.yes') : trans('general.no'),
                    "",
                    "",
                    "",
                    "",
                    'IZ',
                    array_key_exists('field', $izField) ? $izField['field'] : "",
                    array_key_exists('subfield', $izField) ? $izField['subfield'] : "",
                    array_key_exists('code', $izField) ? $izField['code'] : "",
                    "",
                    array_key_exists('name', $izField) ? $izField['name'] : "",
                    array_key_exists('comment', $izField) ? $izField['comment'] : ""
                ];

                $mapped[] = $entry;
            }
        }

        $mapped[] = [
            $catalog->library->bibcode,
            $catalog->library->name,
            $catalog->library->is_active ? trans('general.yes') : trans('general.no'),
            $catalog->is_072 ? trans('general.yes') : trans('general.no'),
            $catalog->is_082 ? trans('general.yes') : trans('general.no'),
            $catalog->is_084 ? trans('general.yes') : trans('general.no'),
            $catalog->catalog_comment,
            'Katalogisier-Info',
            "",
            "",
            "",
            "",
            "",
            $catalog->comment
        ];

        return $mapped;
    }

    public function title(): string
    {
        return trans('export.catalogSheet');
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
}