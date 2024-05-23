@php

@endphp

<div class="modal-header">
<h5 class="modal-title">{{ trans('export.exportCatalog') }}</h5>
</div>
<div class="modal-body">
    <form class="form exportfilter">
        <div class="row">
            <div class="col-4">
                <label for="statefilter" class="form-label">{{ trans('library.plural') }}</label>
                <select class="form-select" id="statefilter" name="active">
                    <option value="">{{ trans('general.all') }}</option>
                    <option value="1" selected>{{ trans('export.active') }}</option>
                    <option value="0">{{ trans('export.inactive') }}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="072filter" class="form-label">{{ trans('libraryCatalog.is072') }}</label>
                <select class="form-select" id="072filter" name="is_072">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="082filter" class="form-label">{{ trans('libraryCatalog.is082') }}</label>
                <select class="form-select" id="082filter" name="is_082">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="084filter" class="form-label">{{ trans('libraryCatalog.is084') }}</label>
                <select class="form-select" id="084filter" name="is_084">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.catalog") }}">{{ trans('general.export') }}</button>
</div>