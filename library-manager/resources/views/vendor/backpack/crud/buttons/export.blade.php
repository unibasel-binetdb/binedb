@if ($crud->hasAccess('update'))
<a href="javascript:void(0)" onclick="ExportModal.exportLibrary(this)" data-route="{{ backpack_url("library/$entry->id/export") }}" class="btn btn-sm btn-link" data-button-type="export">
    <span class="ladda-label"><i class="la la-download"></i> Exportieren</span>
</a>
@endif