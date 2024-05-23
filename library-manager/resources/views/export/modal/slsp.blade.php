@php
$status = \App\Enums\SlspState::values();
$costs = \App\Enums\SlspCost::values();
$usages = \App\Enums\IzUsageCost::values();
$agreements = \App\Enums\SlspAgreement::values();
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportSlsp') }}</h5>
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
                <label for="statusfilter" class="form-label">{{ trans('librarySlsp.status') }}</label>
                <select class="form-select" id="status" name="status">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($status as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="costfilter" class="form-label">{{ trans('librarySlsp.cost') }}</label>
                <select class="form-select" id="costfilter" name="cost">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($costs as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="agreementfilter" class="form-label">{{ trans('librarySlsp.agreement') }}</label>
                <select class="form-select" id="agreementfilter" name="agreement">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($agreements as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="usagefilter" class="form-label">{{ trans('librarySlsp.usage') }}</label>
                <select class="form-select" id="usagefilter" name="usage">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($usages as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.slsp") }}">{{ trans('general.export') }}</button>
</div>