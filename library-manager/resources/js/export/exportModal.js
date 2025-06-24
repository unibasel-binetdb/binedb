function ExportModal($element) {
    var self = this;
    this.element = $element;

    this.open = function (blade) {
        $.ajax({
            url: blade,
            type: 'GET',
            success: function (response) {
                self.element.find('.modal-content').html(response);
                self.bindForm();
                self.element.modal('show');
            }, error: function () {
                new Noty({
                    type: "danger",
                    text: "<strong>Ups!</strong><br>Ansicht konnte nicht geladen werden."
                }).show();
            }
        });
    };

    this.close = function () {
        self.element.modal('hide');
    };

    this.bindForm = function () {
        $('#closebtn').click(function () {
            self.close();
        });

        var $exportBtn = $('#exportbtn').click(function () {
            $exportBtn.prop('disabled', true).prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');

            var endpoint = $(this).data('endpoint');
            if (endpoint == null || endpoint == '')
                return;

            var data = {};

            self.element.find('.form .form-control, .form .form-select').each(function (i, e) {
                var $el = $(e);
                var val = $el.val();
                if (val !== undefined && val != null && val !== '')
                    data[$el.attr('name')] = val;
            });

            self.element.find('.form .form-check-input').each(function (i, e) {
                var $el = $(e);
                if ($el.is(':checked'))
                    data[$el.attr('name')] = '1';
            });

            ExportModal.downloadExport(endpoint, data).then(function () {
                new Noty({
                    type: "success",
                    text: "<strong>Export abgeschlossen</strong><br>Die gewünschten Daten wurden exportiert und als Datei heruntergeladen."
                }).show();

                self.close();
                $exportBtn.prop('disabled', false).find('> .spinner-border').remove();
            }).catch(function () {
                $exportBtn.prop('disabled', false).find('> .spinner-border').remove();
            });
        });
    }
}

ExportModal.downloadExport = function(endpoint, data) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: endpoint,
            type: 'POST',
            data: data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response, status, xhr) {
                var filename = "";
                var disposition = xhr.getResponseHeader('Content-Disposition');

                if (disposition) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }

                try {
                    var blob = new Blob([response], { type: 'application/octet-stream' });
                    if (typeof window.navigator.msSaveBlob !== 'undefined') {
                        // IE workaround
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var URL = window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) {
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");
                            // safari doesn't support this yet
                            if (typeof a.download === 'undefined') {
                                window.location = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                            }
                        } else {
                            window.location = downloadUrl;
                        }

                        resolve();
                    }
                } catch (ex) {
                    new Noty({
                        type: "danger",
                        text: "<strong>Fehlgeschlagen</strong><br>Export konnte nicht generiert werden."
                    }).show();

                    reject();
                }
            }, error: function () {
                new Noty({
                    type: "danger",
                    text: "<strong>Fehlgeschlagen</strong><br>Export konnte nicht generiert werden."
                }).show();

                reject();
            }
        });
    });
}

ExportModal.exportLibrary = function(btn) {
    var $button = $(btn);
    var route = $button.attr('data-route');
    $button.addClass('btn-loading');
    ExportModal.downloadExport(route).finally(function() {
        $button.removeClass('btn-loading');
    });
}