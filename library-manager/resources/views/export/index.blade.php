@extends(backpack_view('blank'))
@basset(base_path('resources/js/export/exportModal.js'))

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">{{ trans('export.title') }}</h1>
        <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">{{ trans('export.lead') }}</p>
    </section>
@endsection

@section('content')
    <div class="row mt-2 g-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>{{ trans('export.libraryTitle') }}</h3>
                    <p>{{ trans('export.libraryLead') }}</p>

                    <div class="exports">
                        <a href="#" data-blade="{{ route("export.modal.library") }}" class="export-link d-block py-1">{{ trans('export.libraryExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.stock") }}" class="export-link d-block py-1">{{ trans('export.stockExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.building") }}" class="export-link d-block py-1">{{ trans('export.buildingExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.slsp") }}" class="export-link d-block py-1">{{ trans('export.slspExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.catalog") }}" class="export-link d-block py-1">{{ trans('export.catalogExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.function") }}" class="export-link d-block py-1">{{ trans('export.functionExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.collection") }}" class="export-link d-block py-1">{{ trans('export.collectionExport') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>{{ trans('export.personTitle') }}</h3>
                    <p>{{ trans('export.personLead') }}</p>

                    <div class="exports">
                        <a href="#" data-blade="{{ route("export.modal.personFunction") }}" class="export-link d-block py-1">{{ trans('export.personFunctionExport') }}</a>
                        <a href="#" data-blade="{{ route("export.modal.contact") }}" class="export-link d-block py-1">{{ trans('export.contactExport') }}</a>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h3>{{ trans('export.bulkTitle') }}</h3>
                    <p>{{ trans('export.bulkLead') }}</p>

                    <div class="exports">
                        <a href="#" data-blade="{{ route("export.modal.bulk") }}" class="export-link d-block py-1">{{ trans('export.bulkExport') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('before_scripts')
    <div id="exportModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="exportbtn">Export</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')
    <script>
        $(document).ready(function () {
            var exportModal = new ExportModal($('#exportModal'));

            $('.export-link').click(function () {
                exportModal.open($(this).data('blade'));
            });
        });
    </script>
@endsection