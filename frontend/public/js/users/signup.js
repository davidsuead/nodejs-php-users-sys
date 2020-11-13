$(function () {
    $('[data-toggle="tooltip"]').tooltip();    
    
    // Endereço
    $("#card-body-endereco").hide();
    $("#card-header-endereco").click(function () {
        if($("#anchor-icon-endereco").hasClass("fa-angle-double-left")) {
            addEndereco();
            $("#card-header-endereco").removeClass("card-header-shadow");
            $("#anchor-icon-endereco").removeClass("fa-angle-double-left").addClass("fa-angle-double-down");
        } else {
            rmEndereco();
            $("#card-header-endereco").addClass("card-header-shadow");
            $("#anchor-icon-endereco").removeClass("fa-angle-double-down").addClass("fa-angle-double-left");
        }        
        $("#card-body-endereco").toggle('slow');
    });

    // Telefones
    $("#card-body-telefone").hide();
    $("#card-header-telefone").click(function () {
        if($("#anchor-icon-telefone").hasClass("fa-angle-double-left")) {
            addTelefone();
            $("#card-header-telefone").removeClass("card-header-shadow");
            $("#anchor-icon-telefone").removeClass("fa-angle-double-left").addClass("fa-angle-double-down");
        } else {
            rmTelefone();
            $("#card-header-telefone").addClass("card-header-shadow");
            $("#anchor-icon-telefone").removeClass("fa-angle-double-down").addClass("fa-angle-double-left");
        }        
        $("#card-body-telefone").toggle('slow');
    });
});

/**
 * Adiciona classe e atributo que torna o input de preenchimento obrigatório
 * na seção endereço
 */
function addEndereco()
{
    let arrRequired = ['enderecoRua','enderecoBairro','enderecoCidade','enderecoUf'];
    arrRequired.forEach(function (value) {
        $("#usuario-endereco").find('.' + value).addClass('required');
        $("#" + value).prop('required',true);
    });
}

/**
 * Remove classe e atributo que torna o input de preenchimento obrigatório
 * na seção endereço
 */
function rmEndereco()
{
    let arrRequired = ['enderecoRua','enderecoBairro','enderecoCidade','enderecoUf'];
    arrRequired.forEach(function (value) {
        $("#usuario-endereco").find('.' + value).removeClass('required');
        $("#" + value).prop('required',false);
    });
}

/**
 * Adiciona classe e atributo que torna o input de preenchimento obrigatório
 * na seção telefone
 */
function addTelefone()
{
    let arrRequired = ['telefonePrincipal'];
    arrRequired.forEach(function (value) {
        $("#usuario-telefone").find('.' + value).addClass('required');
        $("#" + value).prop('required',true);
    });
}

/**
 * Remove classe e atributo que torna o input de preenchimento obrigatório
 * na seção telefone
 */
function rmTelefone()
{
    let arrRequired = ['telefonePrincipal'];
    arrRequired.forEach(function (value) {
        $("#usuario-telefone").find('.' + value).removeClass('required');
        $("#" + value).prop('required',false);
    });
}