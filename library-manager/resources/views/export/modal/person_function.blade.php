@php
$associatedTypes = \App\Enums\AssociatedType::values();
$yesNos = \App\Enums\YesNo::values();
$institutions = \App\Enums\Institution::values();
$slspContacts = \App\Enums\SlspContact::values();
$occupations = \App\Enums\Occupation::values();
$trainings = \App\Enums\Training::values();
$educations = \App\Enums\Education::values();
$stateTypes = \App\Enums\StateType::values();

@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportPersonFunction') }}</h5>
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
                <label for="associatedfilter" class="form-label">{{ trans('library.associatedType') }}</label>
                <select class="form-select" id="associatedfilter" name="associated_type">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($associatedTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
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
        <div class="row">
            <div class="col-4">
                <label for="trainingfilter" class="form-label">{{ trans('person.training') }}</label>
                <select class="form-select" id="trainingfilter" name="training">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($trainings as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="educationfilter" class="form-label">{{ trans('person.education') }}</label>
                <select class="form-select" id="educationfilter" name="education">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($educations as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="workfilter" class="form-label">{{ trans('personFunction.work') }}</label>
                <select class="form-select" id="workfilter" name="work">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($occupations as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="exitedfilter" class="form-label">{{ trans('export.personel') }}</label>
                <select class="form-select" id="exitedfilter" name="exited">
                    <option value="">{{ trans('general.all') }}</option>
                    <option value="0" selected>{{ trans('export.active') }}</option>
                    <option value="1">{{ trans('export.exited') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="addresslistfilter" class="form-label">{{ trans('personFunction.addressList') }}</label>
                <select class="form-select" id="addresslistfilter" name="address_list">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>

            <div class="col-4">
                <label for="emaillistfilter" class="form-label">{{ trans('personFunction.emailList') }}</label>
                <select class="form-select" id="emaillistfilter" name="email_list">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="personalloginfilter" class="form-label">{{ trans('personFunction.personalLogin') }}</label>
                <select class="form-select" id="personalloginfilter" name="personal_login">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="imerspnalloginfilter" class="form-label">{{ trans('personFunction.impersonalLogin') }}</label>
                <select class="form-select" id="imerspnalloginfilter" name="impersonal_login">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    <option value="1">{{ trans('general.yes') }}</option>
                    <option value="0">{{ trans('general.no') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="slspcontactfilter" class="form-label">{{ trans('personFunction.slspContact') }}</label>
                <select class="form-select" id="slspcontactfilter" name="slsp_contact">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($slspContacts as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.personFunction") }}">{{ trans('general.export') }}</button>
</div>