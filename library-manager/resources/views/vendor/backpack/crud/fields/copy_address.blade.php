@includeWhen(!isset($field['wrapper']) || $field['wrapper'] !== false, 'crud::fields.inc.wrapper_start')
<button class="btn mt-2" data-action="copy" data-fields="{{ implode(', ', $field['fields']) }}"><span class="las la-clipboard"></span><span class="ps-1">{{ trans('general.copy') }}</span></button>
@includeWhen(!isset($field['wrapper']) || $field['wrapper'] !== false, 'crud::fields.inc.wrapper_end')

@push('crud_fields_scripts')
    @bassetBlock('fields/copyaddress.js')
        <script>
            function copyToClipboard(text) {
                var deferred = new $.Deferred();

                try {
                    navigator.clipboard.writeText(text);
                    deferred.resolve();
                } catch (err) {
                    deferred.reject();
                }

                return deferred.promise();
            }

            $(document).ready(function() {
                $('.btn[data-action="copy"]').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var btn = $(e.currentTarget);
                    var fields = btn.attr('data-fields').split(',').map(function(a) { return a.trim() });

                    var text = '';
                    for(var i in fields) {
                        var field = $('.form-control[name="' + fields[i] + '"]');
                        if(field.length !== 0) {
                            var val = field.val();
                            if(val === '')
                                continue;

                            text += val + '\n';
                        }
                    }

                    copyToClipboard(text).then(function() {
                        new Noty({
                            type: "success",
                            text: 'Adresse wurde in die Zwischenablage kopiert.',
                        }).show();
                    });
                });
            });
        </script>
    @endBassetBlock
@endpush
