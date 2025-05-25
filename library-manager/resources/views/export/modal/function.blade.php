@php
$yesNos = \App\Enums\YesNo::values();
$yesNoAlma = \App\Enums\YesNoAlma::values();
$acquisitions = \App\Enums\Acquisition::values();
$subjectIndexings = \App\Enums\SubjectIndexing::values();
$slspCarriers = \App\Enums\SlspCarrier::values();
$printDaemons = \App\Enums\PrintDaemon::values();
$digitizations = \App\Enums\Digitization::values();
$slsKeys = \App\Enums\SlsKey::values();
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportFunction') }}</h5>
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
                <label for="catalogingfilter" class="form-label">{{ trans('libraryFunction.cataloging') }}</label>
                <select class="form-select" id="catalogingfilter" name="cataloging">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="sbjidxlocalfilter" class="form-label">{{ trans('libraryFunction.subjectIdxLocal') }}</label>
                <select class="form-select" id="sbjidxlocalfilter" name="subject_idx_local">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="sbjidxgndfilter" class="form-label">{{ trans('libraryFunction.subjectIdxGnd') }}</label>
                <select class="form-select" id="sbjidxgndfilter" name="subject_idx_gnd">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($subjectIndexings as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="acquisitionfilter" class="form-label">{{ trans('libraryFunction.acquisition') }}</label>
                <select class="form-select" id="acquisitionfilter" name="acquisition">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($acquisitions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="magazinemantfilter" class="form-label">{{ trans('libraryFunction.magazineManagement') }}</label>
                <select class="form-select" id="magazinemantfilter" name="magazine_management">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="lendingfilter" class="form-label">{{ trans('libraryFunction.lending') }}</label>
                <select class="form-select" id="lendingfilter" name="lending">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNoAlma as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="emediafilter" class="form-label">{{ trans('libraryFunction.emedia') }}</label>
                <select class="form-select" id="emediafilter" name="emedia">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="digitizationfilter" class="form-label">{{ trans('libraryFunction.digitization') }}</label>
                <select class="form-select" id="digitizationfilter" name="digitization">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($digitizations as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="slskeyfilter" class="form-label">{{ trans('libraryFunction.slsKey') }}</label>
                <select class="form-select" id="slskeyfilter" name="slskey">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($slsKeys as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="selflendingfilter" class="form-label">{{ trans('libraryFunction.selfLending') }}</label>
                <select class="form-select" id="selflendingfilter" name="self_lending">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="baselcarrierfilter" class="form-label">{{ trans('libraryFunction.baselCarrier') }}</label>
                <select class="form-select" id="baselcarrierfilter" name="basel_carrier">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="slspcarrierfilter" class="form-label">{{ trans('libraryFunction.slspCarrier') }}</label>
                <select class="form-select" id="slspcarrierfilter" name="slsp_carrier">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($slspCarriers as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="rfidfilter" class="form-label">{{ trans('libraryFunction.rfid') }}</label>
                <select class="form-select" id="rfidfilter" name="rfid">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($yesNos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="slspbursarfilter" class="form-label">{{ trans('libraryFunction.slspBursar') }}</label>
                <select class="form-select" id="slspbursarfilter" name="slsp_bursar">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($slspCarriers as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="printdaemonfilter" class="form-label">{{ trans('libraryFunction.printDaemon') }}</label>
                <select class="form-select" id="printdaemonfilter" name="print_daemon">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($printDaemons as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.function") }}">{{ trans('general.export') }}</button>
</div>