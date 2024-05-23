<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\LibraryFunction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class FunctionExport implements FromQuery, WithEvents, WithMapping, WithHeadings
{
    use ExcelColumnAutoSize;
    
    private $onlyActive = NULL;
    private $cataloging = NULL;
    private $subjectIdxLocal = NULL;
    private $subjectIdxGnd = NULL;
    private $acquisition = NULL;
    private $magazineManagement = NULL;
    private $lending = NULL;
    private $selfLending = NULL;
    private $baselCarrier = NULL;
    private $slspCarrier = NULL;
    private $rfid = NULL;
    private $slspBursar = NULL;
    private $printDaemon = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $this->cataloging = $request->input('cataloging');
        $this->subjectIdxLocal = $request->input('subject_idx_local');
        $this->subjectIdxGnd = $request->input('subject_idx_gnd');
        $this->acquisition = $request->input('acquisition');
        $this->magazineManagement = $request->input('magazine_management');
        $this->lending = $request->input('lending');
        $this->selfLending = $request->input('self_lending');
        $this->baselCarrier = $request->input('basel_carrier');
        $this->slspCarrier = $request->input('slsp_carrier');
        $this->rfid = $request->input('rfid');
        $this->slspBursar = $request->input('slsp_bursar');
        $this->printDaemon = $request->input('print_daemon');

        return $this;
    }

    public function query()
    {
        $qry = LibraryFunction::query();
        $qry->join('libraries', 'library_functions.library_id', '=', 'libraries.id');

        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);
        
        if ($this->cataloging !== NULL)
            $qry->where('cataloging', $this->cataloging);

        if ($this->subjectIdxLocal !== NULL)
            $qry->where('subject_idx_local', $this->subjectIdxLocal);

        if ($this->subjectIdxGnd !== NULL)
            $qry->where('subject_idx_gnd', $this->subjectIdxGnd);

        if ($this->acquisition !== NULL)
            $qry->where('acquisition', $this->acquisition);

        if ($this->magazineManagement !== NULL)
            $qry->where('magazine_management', $this->magazineManagement);

        if ($this->lending !== NULL)
            $qry->where('lending', $this->lending);

        if ($this->selfLending !== NULL)
            $qry->where('self_lending', $this->selfLending);

        if ($this->baselCarrier !== NULL)
            $qry->where('basel_carrier', $this->baselCarrier);

        if ($this->slspCarrier !== NULL)
            $qry->where('slsp_carrier', $this->slspCarrier);

        if ($this->rfid !== NULL)
            $qry->where('rfid', $this->rfid);

        if ($this->slspBursar !== NULL)
            $qry->where('slsp_bursar', $this->slspBursar);

        if ($this->printDaemon !== NULL)
            $qry->where('print_daemon', $this->printDaemon);

        return $qry->orderBy('libraries.bibcode', 'asc');
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('libraryFunction.cataloging'),
            trans('libraryFunction.catalogingComment'),
            trans('libraryFunction.subjectIdxLocal'),
            trans('libraryFunction.subjectIdxGnd'),
            trans('libraryFunction.subjectIdxComment'),
            trans('libraryFunction.acquisition'),
            trans('libraryFunction.acquisitionComment'),
            trans('libraryFunction.magazineManagement'),
            trans('libraryFunction.magazineManagementComment'),
            trans('libraryFunction.lending'),
            trans('libraryFunction.lendingComment'),
            trans('libraryFunction.selfLending'),
            trans('libraryFunction.selfLendingComment'),
            trans('libraryFunction.baselCarrier'),
            trans('libraryFunction.baselCarrierComment'),
            trans('libraryFunction.slspCarrier'),
            trans('libraryFunction.slspCarrierComment'),
            trans('libraryFunction.rfid'),
            trans('libraryFunction.rfidComment'),
            trans('libraryFunction.slspBursar'),
            trans('libraryFunction.slspBursarComment'),
            trans('libraryFunction.printDaemon'),
            trans('libraryFunction.printDaemonComment')
        ];
    }

    public function map($function): array
    {
        return [
            $function->library->bibcode,
            $function->library->name,
            $function->library->is_active ? trans('general.yes') : trans('general.no'),
            $function->cataloging?->translate(),
            $function->cataloging_comment,
            $function->subject_idx_local?->translate(),
            $function->subject_idx_gnd?->translate(),
            $function->subject_idx_comment,
            $function->acquisition?->translate(),
            $function->acquisition_comment,
            $function->magazine_management?->translate(),
            $function->magazine_management_comment,
            $function->lending?->translate(),
            $function->lending_comment,
            $function->self_lending?->translate(),
            $function->self_lending_comment,
            $function->basel_carrier?->translate(),
            $function->basel_carrier_comment,
            $function->slsp_carrier?->translate(),
            $function->slsp_carrier_comment,
            $function->rfid?->translate(),
            $function->rfid_comment,
            $function->slsp_bursar?->translate(),
            $function->slsp_bursar_comment,
            $function->print_daemon?->translate(),
            $function->print_daemon_comment
        ];
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