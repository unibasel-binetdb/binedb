<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BuildingExport;
use App\Exports\CatalogExport;
use App\Exports\CollectionExport;
use App\Exports\ContactExport;
use App\Exports\FunctionExport;
use App\Exports\LibraryExport;
use App\Exports\PersonFunctionExport;
use App\Exports\SlspExport;
use App\Exports\StockExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        return view('export.index');
    }

    public function exportLibraries(Request $request)
    {
        return Excel::download((new LibraryExport)->filter($request), trans('export.libraryExport').'.xlsx');
    }

    public function libraryModal()
    {
        return view('export.modal.library');
    }

    public function exportStocks(Request $request)
    {
        return Excel::download((new StockExport)->filter($request), trans('export.stockExport').'.xlsx');
    }

    public function stockModal()
    {
        return view('export.modal.stock');
    }

    public function exportBuildings(Request $request)
    {
        return Excel::download((new BuildingExport)->filter($request), trans('export.buildingExport').'.xlsx');
    }

    public function buildingModal()
    {
        return view('export.modal.building');
    }

    public function exportSlsps(Request $request)
    {
        return Excel::download((new SlspExport)->filter($request), trans('export.slspExport').'.xlsx');
    }

    public function slspModal()
    {
        return view('export.modal.slsp');
    }

    public function exportCatalogs(Request $request)
    {
        return Excel::download((new CatalogExport)->filter($request), trans('export.catalogExport').'.xlsx');
    }

    public function catalogModal()
    {
        return view('export.modal.catalog');
    }

    public function exportFunctions(Request $request)
    {
        return Excel::download((new FunctionExport)->filter($request), trans('export.functionExport').'.xlsx');
    }

    public function functionModal()
    {
        return view('export.modal.function');
    }

    public function exportCollections(Request $request)
    {
        return Excel::download((new CollectionExport)->filter($request), trans('export.collectionExport').'.xlsx');
    }

    public function collectionModal()
    {
        return view('export.modal.collection');
    }

    public function exportPersonFunctions(Request $request)
    {
        return Excel::download((new PersonFunctionExport)->filter($request), trans('export.personFunctionExport').'.xlsx');
    }

    public function personFunctionModal()
    {
        return view('export.modal.person_function');
    }

    public function exportContacts(Request $request)
    {
        return Excel::download((new ContactExport)->filter($request), trans('export.contactExport').'.xlsx');
    }

    public function contactModal()
    {
        return view('export.modal.contact');
    }
}