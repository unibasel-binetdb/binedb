@php
$associatedTypes = \App\Enums\AssociatedType::values();
$faculties = \App\Enums\Faculty::values();
$providers = \App\Enums\Provider::values();
$yesNos = \App\Enums\YesNo::values();
$stateTypes = \App\Enums\StateType::values();

@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportLibrary') }}</h5>
</div>
<div class="modal-body">
    <form class="form exportfilter">
        <div class="row">
            <div class="col-4">
                <label for="statefilter" class="form-label">{{ trans('library.activeInactive') }}</label>
                <select class="form-select" id="statefilter" name="active">
                    <option value="">{{ trans('general.all') }}</option>
                    <option value="1" selected>{{ trans('export.active') }}</option>
                    <option value="0">{{ trans('export.inactive') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="associatedfilter" class="form-label">{{ trans('library.associatedType') }}</label>
                <select class="form-select" id="associatedfilter" name="associated_type">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($associatedTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="facultyfilter" class="form-label">{{ trans('library.faculty') }}</label>
                <select class="form-select" id="facultyfilter" name="faculty">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($faculties as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="providerfilter" class="form-label">{{ trans('library.itProvider') }}</label>
                <select class="form-select" id="providerfilter" name="it_provider">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($providers as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="izlibraryfilter" class="form-label">{{ trans('library.izLibrary') }}</label>
                <select class="form-select" id="izlibraryfilter" name="iz_library">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="statetypefilter" class="form-label">{{ trans('library.stateType') }}</label>
                <select class="form-select" id="statetypefilter" name="state_type">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($stateTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.library") }}">{{ trans('general.export') }}</button>
</div>