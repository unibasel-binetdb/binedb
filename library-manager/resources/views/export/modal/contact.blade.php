@php
$topics = \App\Enums\ContactTopic::values();
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.exportContact') }}</h5>
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
                <label for="exitedfilter" class="form-label">{{ trans('export.personel') }}</label>
                <select class="form-select" id="exitedfilter" name="exited">
                    <option value="">{{ trans('general.all') }}</option>
                    <option value="0" selected>{{ trans('export.active') }}</option>
                    <option value="1">{{ trans('export.exited') }}</option>
                </select>
            </div>
            <div class="col-4">
                <label for="topicfilter" class="form-label">{{ trans('contact.topic') }}</label>
                <select class="form-select" id="topicfilter" name="topic">
                    <option value="" selected>{{ trans('general.all') }}</option>
                    @foreach($topics as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.contact") }}">{{ trans('general.export') }}</button>
</div>