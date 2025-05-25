<div class="modal-header">
    <h5 class="modal-title">{{ trans('export.bulkTitle') }}</h5>
</div>
<div class="modal-body">
    <form class="form exportfilter">
        <div class="row">
            <div class="col-4">
                <label for="library" class="form-label">{{ trans('export.librarySheet') }}</label>
                <input type="checkbox" class="form-check-input" id="library" name="library" value="1" checked />
            </div>
            <div class="col-4">
                <label for="stock" class="form-label">{{ trans('export.stockSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="stock" name="stock" value="1" checked />
            </div>
            <div class="col-4">
                <label for="building" class="form-label">{{ trans('export.buildingSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="building" name="building" value="1" checked />
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="slsp" class="form-label">{{ trans('export.slspSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="slsp" name="slsp" value="1" checked />
            </div>
            <div class="col-4">
                <label for="catalog" class="form-label">{{ trans('export.catalogSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="catalog" name="catalog" value="1" checked />
            </div>
            <div class="col-4">
                <label for="function" class="form-label">{{ trans('export.functionSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="function" name="function" value="1" checked />
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for="collection" class="form-label">{{ trans('export.collectionSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="collection" name="collection" value="1" checked />
            </div>
            <div class="col-4">
                <label for="person_function" class="form-label">{{ trans('export.personFunctionSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="person_function" name="person_function" value="1" checked />
            </div>
            <div class="col-4">
                <label for="contact" class="form-label">{{ trans('export.contactSheet') }}</label>
                <input type="checkbox" class="form-check-input" id="contact" name="contact" value="1" checked />
            </div>
        </div>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <a href="#" class="text-btn" id="closebtn">{{ trans('general.cancel') }}</a>
    <button type="button" class="btn btn-primary" id="exportbtn" data-endpoint="{{ route("export.bulk") }}">{{ trans('general.export') }}</button>
</div>