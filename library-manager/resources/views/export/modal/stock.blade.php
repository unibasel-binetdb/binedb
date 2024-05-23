@php

@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportStock') }}</h5>
</div>
<div class="modal-body">
    <form class="form exportfilter">
        <div class="row">
            <div class="col-6">
                <label for="statefilter" class="form-label">{{ trans('library.plural') }}</label>
                <select class="form-select" id="statefilter" name="active">
                    <option value="">{{ trans('general.all') }}</option>
                    <option value="1" selected>{{ trans('export.active') }}</option>
                    <option value="0">{{ trans('export.inactive') }}</option>
                </select>
            </div>
            <div class="col-6">
                <label for="specialstockfilter" class="form-label">{{ trans('libraryStock.isSpecialStock') }}</label>
                <select class="form-select" id="specialstockfilter" name="is_special_stock">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <label for="ubdepositumfilter" class="form-label">{{ trans('libraryStock.isDepositum') }}</label>
                <select class="form-select" id="ubdepositumfilter" name="is_depositum">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
            <div class="col-6">
                <label for="institutedepositumfilter" class="form-label">{{ trans('libraryStock.isInstDepositum') }}</label>
                <select class="form-select" id="institutedepositumfilter" name="is_inst_depositum">
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
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.stock") }}">{{ trans('general.export') }}</button>
</div>