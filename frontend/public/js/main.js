$(document).ready(function () {
    $("#error-feedback").hide();
    $("#success-feedback").hide();
});

function limpaMensagem() 
{
    $("#error-feedback").hide();
    $("#success-feedback").html('');
}

function showError(msg)
{
    $("#error-feedback").html(msg);
    $("#error-feedback").fadeIn('slow');
    scroll(0,0);
}

function showSuccess(msg)
{
    if(msg) {
        $("#success-feedback").html(msg);
        $("#success-feedback").fadeIn('slow');
        scroll(0,0);
    }
}

function submitForm(idForm, btn) {
    if ($('.' + btn).attr('disabled') !== 'disabled') {
        $('.' + btn).attr('disabled', true);
        if ($('#' + idForm).attr('enctype') == 'multipart/form-data') {
            var file = document.getElementById("fileUploaded").files[0];
            var formData = new FormData();
            formData.append("fileUploaded", file);

            var objAjax = {
                url: $('#' + idForm).attr('url'),
                method: $('#' + idForm).attr('method'),
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
            };
        } else {
            var objAjax = {
                url: $('#' + idForm).attr('url'),
                method: $('#' + idForm).attr('method'),
                data: $('#' + idForm).serialize(),
                dataType: 'json'
            };
        }
        $.ajax(objAjax).done(function (data) {
            limpaMensagem();
            //Habilita botão
            $('.' + btn).attr('disabled', false);
            if (data.class == 'success') {
                if (data.url) {
                    showSuccess(data.successMsg);
                    if (data.successTime && data.successTime > 0) {
                        setTimeout(() => {
                            window.location = baseURL + data.url;
                        }, data.successTime);
                    }
                } else {
                    window.location.reload();
                }
            } else {
                showError(data.msg);
            }
        }).fail(function (settings) {
            limpaMensagem();
            $('.' + btn).attr('disabled', false);
            showError(settings.responseJSON.msg);
        });
    }
}