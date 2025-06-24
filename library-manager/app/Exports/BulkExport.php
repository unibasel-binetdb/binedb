<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BulkExport implements WithMultipleSheets
{
    protected ?int $onlyLibraryId = null;

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
    protected array $exportedClasses = [];

    public function filter(Request $request): self
    {
        foreach ($this->availableExports as $key => $exportClass) {
            $value = $request->input($key);
            if ($value !== null && $value == "1") {
                $this->addExport(new $exportClass());
            }
        }

        return $this;
    }

    public function forLibrary($libraryId): self
    {
        foreach ($this->availableExports as $key => $exportClass) {
            $exporter = new $exportClass();
            if (method_exists($exporter, 'forLibrary')) {
                $exporter->forLibrary($libraryId);
            }
            $this->addExport($exporter);
        }

        return $this;
    }

    public function sheets(): array
    {
        return $this->enabledExports;
    }

    protected function addExport(object $exporter): void
    {
        $class = get_class($exporter);

        if (!in_array($class, $this->exportedClasses)) {
            $this->enabledExports[] = $exporter;
            $this->exportedClasses[] = $class;
        }
    }
}
