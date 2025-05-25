<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BulkExport implements WithMultipleSheets
{
    protected array $availableExports = [
        'library' => LibraryExport::class,
        'stock' => StockExport::class,
        'building' => BuildingExport::class,
        'slsp' => SlspExport::class,
        'catalog' => CatalogExport::class,
        'function' => FunctionExport::class,
        'collection' => CollectionExport::class,
        'person_function' => PersonFunctionExport::class,
        'contact' => ContactExport::class,
    ];

    protected array $enabledExports = [];

    public function filter(Request $request): self
    {
        foreach ($this->availableExports as $key => $exportClass) {
            $value = $request->input($key);
            if ($value !== null && $value == "1") {
                $this->enabledExports[] = new $exportClass();
            }
        }

        return $this;
    }

    public function sheets(): array
    {
        return $this->enabledExports;
    }
}
