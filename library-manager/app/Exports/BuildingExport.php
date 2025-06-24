<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\LibraryBuilding;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class BuildingExport implements FromQuery, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;

    private ?int $onlyLibraryId = NULL;
    private $onlyActive = NULL;
    private $onlyKey = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $onlyKey = $request->input('key');
        if ($onlyKey !== NULL)
            $this->onlyKey = $onlyKey == "1";

        return $this;
    }

    public function forLibrary($libraryId): self {
        $this->onlyLibraryId = $libraryId;

        return $this;
    }

    public function query()
    {
        $qry = LibraryBuilding::query();
        $qry->join('libraries', 'library_buildings.library_id', '=', 'libraries.id');

        if($this->onlyLibraryId !== NULL)
            $qry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);

        if ($this->onlyKey !== NULL)
            $qry->where('key', $this->onlyKey);

        return $qry->orderBy('libraries.bibcode', 'asc');
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('libraryBuilding.copier'),
            trans('libraryBuilding.additionalDevices'),
            trans('libraryBuilding.comment'),
            trans('libraryBuilding.key'),
            trans('libraryBuilding.keyDepot'),
            trans('libraryBuilding.keyComment'),
            trans('libraryBuilding.operatingArea'),
            trans('libraryBuilding.audienceArea'),
            trans('libraryBuilding.staffWorkspaces'),
            trans('libraryBuilding.audienceWorkspaces'),
            trans('libraryBuilding.workspaceComment'),
            trans('libraryBuilding.spaceComment')
        ];
    }

    public function map($building): array
    {
        return [
            $building->library->bibcode,
            $building->library->name,
            $building->library->is_active ? trans('general.yes') : trans('general.no'),
            $building->copier,
            $building->additional_devices,
            $building->comment,
            $building->key ? trans('general.yes') : trans('general.no'),
            $building->key_depot,
            $building->key_comment,
            $building->operating_area,
            $building->audience_area,
            $building->staff_workspaces,
            $building->audience_workspaces,
            $building->workspace_comment,
            $building->space_comment
        ];
    }

    public function title(): string
    {
        return trans('export.buildingSheet');
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