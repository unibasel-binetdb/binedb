@php
$usageUnits = \App\Enums\UsageUnit::values();
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportCollection') }}</h5>
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
            <div class="col-4">
                <label for="usageunitfilter" class="form-label">{{ trans('location.usageUnit') }}</label>
                <select class="form-select" id="usageunitfilter" name="usage_unit">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($usageUnits as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.collection") }}">{{ trans('general.export') }}</button>
</div>