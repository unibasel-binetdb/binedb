<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\LibrarySlsp;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class SlspExport implements FromQuery, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;
    
    private ?int $onlyLibraryId = NULL;
    private $onlyActive = NULL;
    private $status = NULL;
    private $cost = NULL;
    private $usage = NULL;
    private $agreement = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $this->status = $request->input('status');
        $this->cost = $request->input('cost');
        $this->usage = $request->input('usage');
        $this->agreement = $request->input('agreement');

        return $this;
    }

    public function forLibrary($libraryId): self {
        $this->onlyLibraryId = $libraryId;

        return $this;
    }

    public function query()
    {
        $qry = LibrarySlsp::query();
        $qry->join('libraries', 'library_slsps.library_id', '=', 'libraries.id');

        if($this->onlyLibraryId !== NULL)
            $qry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);
        
        if ($this->status !== NULL)
            $qry->where('status', $this->status);

        if ($this->cost !== NULL)
            $qry->where('cost', $this->cost);

        if ($this->usage !== NULL)
            $qry->where('usage', $this->usage);

        if ($this->agreement !== NULL)
            $qry->where('agreement', $this->agreement);

        return $qry->orderBy('libraries.bibcode', 'asc');
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('librarySlsp.status'),
            trans('librarySlsp.statusComment'),
            trans('librarySlsp.cost'),
            trans('librarySlsp.usage'),
            trans('librarySlsp.costComment'),
            trans('librarySlsp.agreement'),
            trans('librarySlsp.agreementComment'),
            trans('librarySlsp.ftes'),
            trans('librarySlsp.fteComment'),
            trans('librarySlsp.comment')
        ];
    }

    public function map($slsp): array
    {
        return [
            $slsp->library->bibcode,
            $slsp->library->name,
            $slsp->library->is_active ? trans('general.yes') : trans('general.no'),
            $slsp->status?->translate(),
            $slsp->status_comment,
            $slsp->cost?->translate(),
            $slsp->usage?->translate(),
            $slsp->cost_comment,
            $slsp->agreement?->translate(),
            $slsp->agreement_comment,
            $slsp->ftes,
            $slsp->fte_comment,
            $slsp->comment
        ];
    }

    public function title(): string
    {
        return trans('export.slspSheet');
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