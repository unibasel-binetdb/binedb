<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\Library;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class LibraryExport implements FromQuery, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;

    private ?int $onlyLibraryId = NULL;
    private $onlyActive = NULL;
    private $onlyIzLibrary = NULL;
    private $associatedType = NULL;
    private $faculty = NULL;
    private $itProvider = NULL;
    private $stateType = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $onlyIzLibrary = $request->input('iz_library');
        if ($onlyIzLibrary !== NULL)
            $this->onlyIzLibrary = $onlyIzLibrary == "1";

        $this->associatedType = $request->input('associated_type');
        $this->faculty = $request->input('faculty');
        $this->itProvider = $request->input('it_provider');
        $this->stateType = $request->input('state_type');

        return $this;
    }
    public function forLibrary($libraryId): self {
        $this->onlyLibraryId = $libraryId;

        return $this;
    }

    public function query()
    {
        $qry = Library::query();

        if($this->onlyLibraryId !== NULL)
            $qry->where('id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $qry->where('is_active', $this->onlyActive);

        if ($this->onlyIzLibrary !== NULL)
            $qry->where('iz_library', $this->onlyIzLibrary);

        if ($this->associatedType !== NULL)
            $qry->where('associated_type', $this->associatedType);

        if ($this->faculty !== NULL)
            $qry->where('faculty', $this->faculty);

        if ($this->itProvider !== NULL)
            $qry->where('it_provider', $this->itProvider);

        if ($this->stateType !== NULL)
            $qry->where('state_type', $this->stateType);

        return $qry->orderBy('libraries.bibcode', 'asc');
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.shortName'),
            trans('library.name'),
            trans('library.nameAddition'),
            trans('library.alternativeName'),
            trans('library.existingSince'),
            trans('library.activeInactive'),
            trans('library.institutionUrl'),
            trans('library.libraryUrl'),
            trans('library.shippingPoBox'),
            trans('library.shippingStreet'),
            trans('library.shippingZip'),
            trans('library.shippingLocation'),
            trans('library.differentBillingAddress'),
            trans('library.billingName'),
            trans('library.billingNameAddition'),
            trans('library.billingPoBox'),
            trans('library.billingStreet'),
            trans('library.billingZip'),
            trans('library.billingLocation'),
            trans('library.billingComment'),
            trans('library.libraryComment'),
            trans('library.associatedType'),
            trans('library.associatedComment'),
            trans('library.faculty'),
            trans('library.departement'),
            trans('library.uniRegulations'),
            trans('library.bibstatsIdentification'),
            trans('library.uniCostcenter'),
            trans('library.ubCostcenter'),
            trans('library.financeComment'),
            trans('library.itProvider'),
            trans('library.ipAddress'),
            trans('library.itComment'),
            trans('library.izLibrary'),
            trans('library.stateType'),
            trans('library.stateSince'),
            trans('library.stateUntil'),
            trans('library.stateComment')
        ];
    }

    public function map($library): array
    {
        return [
            $library->bibcode,
            $library->short_name,
            $library->name,
            $library->name_addition,
            $library->alternative_name,
            $library->existing_since,
            $library->is_active ? trans('library.isActive') : trans('library.isInactive'),
            $library->institution_url,
            $library->library_url,
            $library->shipping_pobox,
            $library->shipping_street,
            $library->shipping_zip,
            $library->shipping_location,
            $library->different_billing_address,
            $library->billing_name,
            $library->billing_name_addition,
            $library->billing_pobox,
            $library->billing_street,
            $library->billing_zip,
            $library->billing_location,
            $library->billing_comment,
            $library->library_comment,
            $library->associated_type?->translate(),
            $library->associated_comment,
            $library->faculty?->translate(),
            $library->departement,
            $library->uni_regulations,
            $library->bibstats_identification,
            $library->uni_costcenter,
            $library->ub_costcenter,
            $library->finance_comment,
            $library->it_provider?->translate(),
            $library->ip_address,
            $library->it_comment,
            $library->iz_library ? trans('general.yes') : trans('general.no'),
            $library->state_type?->translate(),
            $library->state_since,
            $library->state_until,
            $library->state_comment
        ];
    }

    public function title(): string
    {
        return trans('export.librarySheet');
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