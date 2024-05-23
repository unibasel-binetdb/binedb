<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\LibraryStock;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class StockExport implements FromQuery, WithEvents, WithMapping, WithHeadings
{
    use ExcelColumnAutoSize;
    
    private $onlyActive = NULL;
    private $onlySpecialStock = NULL;
    private $onlyUbDepositum = NULL;
    private $onlyStockDepositum = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";
        
        $onlySpecialStock = $request->input('is_special_stock');
        if ($onlySpecialStock !== NULL)
            $this->onlySpecialStock = $onlySpecialStock == "1";

        $onlyUbDepositum = $request->input('is_depositum');
        if ($onlyUbDepositum !== NULL)
            $this->onlyUbDepositum = $onlyUbDepositum == "1";

        $onlyStockDepositum = $request->input('is_inst_depositum');
        if ($onlyStockDepositum !== NULL)
            $this->onlyStockDepositum = $onlyStockDepositum == "1";

        return $this;
    }

    public function query()
    {
        $qry = LibraryStock::query();
        $qry->join('libraries', 'library_stocks.library_id', '=', 'libraries.id');

        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);

        if ($this->onlySpecialStock !== NULL)
            $qry->where('is_special_stock', $this->onlySpecialStock);

        if ($this->onlyUbDepositum !== NULL)
            $qry->where('is_depositum', $this->onlyUbDepositum);

        if ($this->onlyStockDepositum !== NULL)
            $qry->where('is_inst_depositum', $this->onlyStockDepositum);

        return $qry->orderBy('libraries.bibcode', 'asc');
    }

    public function headings(): array
    {
        return [
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('libraryStock.isSpecialStock'),
            trans('libraryStock.specialStockComment'),
            trans('libraryStock.isDepositum'),
            trans('libraryStock.isInstDepositum'),
            trans('libraryStock.instDepositumComment'),
            trans('libraryStock.pushback'),
            trans('libraryStock.pushback2010'),
            trans('libraryStock.pushback2020'),
            trans('libraryStock.pushback2030'),
            trans('libraryStock.memoryLibrary'),
            trans('libraryStock.running1899'),
            trans('libraryStock.running1999'),
            trans('libraryStock.running2000'),
            trans('libraryStock.runningZss1899'),
            trans('libraryStock.runningZss1999'),
            trans('libraryStock.runningZss2000'),
            trans('libraryStock.stockComment')
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->library->bibcode,
            $stock->library->name,
            $stock->library->is_active ? trans('general.yes') : trans('general.no'),
            $stock->is_special_stock ? trans('general.yes') :  trans('general.no'),
            $stock->special_stock_comment,
            $stock->is_depositum ? trans('general.yes') :  trans('general.no'),
            $stock->is_inst_depositum ? trans('general.yes') :  trans('general.no'),
            $stock->inst_depositum_comment,
            $stock->pushback,
            $stock->pushback_2010,
            $stock->pushback_2020,
            $stock->pushback_2030,
            $stock->memory_library,
            $stock->running_1899,
            $stock->running_1999,
            $stock->running_2000,
            $stock->running_zss_1899,
            $stock->running_zss_1999,
            $stock->running_zss_2000,
            $stock->comment
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