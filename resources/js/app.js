require('./bootstrap');

require('alpinejs');

import Swal from 'sweetalert2'

$(document).ready(function(){
    let ajaxForm = $('form.ajax-form');
    let progress = $('#progress'); let progressbar = $(progress).find('#progressbar');
    let withFile  =$('form.withFile');
    let vote = $('a.vote');
    let destroyForm = $('form.destroy');

    $(destroyForm).each(function(){
        $(this).on('submit', (e) => {
            e.preventDefault();
            let method = $(this).find('input[name="_method"').val() || $(this).attr('method');
            let form = $(this);
            let url = $(this).attr('action');

            Swal.fire({
                title: 'Supprimer?',
                text: 'Veuillez confirmer la suppression',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Annuler',
                allowOutsideClick: false,
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: url,
                        type: method,
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: (response) => {
                            if(response.success){
                                let redirect = response.redirect || null;
                                handleSuccess(response.success, redirect);
                            }
                        },
                        error: (xhr, status, err) => {
                            handleErrors(xhr);
                        }
                    })
                }
            })
        })
    })

    $(vote).each(function(){
        $(this).on('click', (e) => {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                dataType: 'json',
                success: (response) => {
                    if(response.success){
                        let redirect = response.redirect || null;
                        handleSuccess(response.success, redirect);
                    }
                    if(response.error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur !',
                            html: response.error,
                        })
                    }
                },
                error: (xhr, status, err) => {
                    handleErrors(xhr);
                }
            })
        })
    })

    $(withFile).each(function(){
        $(this).on('submit', (e) => {
            e.preventDefault();

            let form = $(this);
            let method = $(this).find('input[name="_method"').val() || $(this).attr('method');
            let url = $(this).attr('action');
            let data = new FormData(this);
            let button = $(this).find('button');
            $(button).prop('disabled', true);
            let inputFile = $(this).find('input[type="file"]');
            let file = $(inputFile).get(0).files;

            if($(file).length)
            {
                let filename = $(inputFile).get(0).files[0].name;
                data.append(filename, $(inputFile.get(0).files[0]));

                $(progress).show();

                var config = {
                    url: url,
                    method: method,
                    data: data,
                    responseType: 'json',
                    onUploadProgress: (e) => {
                        let percentCompleted = Math.round((e.loaded * 100) / e.total);
                        console.log(percentCompleted);
                        $(progressbar).width(percentCompleted+'%').text(percentCompleted+'%');
                        if(percentCompleted == 100){
                            $(progress).fadeOut().width('0%').text('0%');
                        }
                    }
                }

                axios(config)
                    .then(function(response){
                        $(button).prop('disabled', false);
                        console.log(response.data);
                        if(response.data.success){
                            let redirect = response.data.redirect || null;
                            handleSuccess(response.data.success, redirect);
                        }
                    })
                    .catch(function(error){
                        $(button).prop('disabled', false);
                        if(error.response){
                            if(error.response.status === 422 && error.response.data.errors){
                                console.log(error.response.data.errors);
                                let errorString = '';
                                $.each(error.response.data.errors, function(key, value){
                                    errorString += '<p>'+value+'</p>';
                                })
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops... 😕',
                                    html: errorString,
                                })
                                return false;
                            }
                            handleErrors(error.response);

                        } else if (error.request) {

                            console.log(error.request);
                        } else {
                            console.log('Error', error.message);
                        }
                        console.log(error.config);
                    });
            }
            else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oup!',
                    text: 'Veuillez ajouter une image',
                })
            }
        })
    })

    $(ajaxForm).each(function(){
        $(this).on('submit', (e) => {
            e.preventDefault();
            let method = $(this).find('input[name="_method"').val() || $(this).attr('method');
            let data = $(this).serialize();
            $.ajax({
                type: method,
                url: $(this).attr('action'),
                data: data,
                dataType: 'json',
                success: (response) => {
                    console.log(response);
                    if(response.success){
                        let redirect = response.redirect || null;
                        handleSuccess(response.success, redirect);
                    }
                },
                error: (xhr, status, err) => {
                    //console.log(xhr, status, err);
                    handleErrors(xhr);
                }
            })
        })
    })

    function handleSuccess(success, redirect)
    {
        Swal.fire({
            icon: 'success',
            title: 'Ok',
            html: success,
            allowOutsideClick: false,
        }).then((result) => {
            if(result.value){
                if(redirect){
                    window.location = redirect;
                }
            }
        })
    }

    function handleErrors(xhr)
    {
        switch (xhr.status) {
            case 422: //erreur validation
                let errorString = '';
                $.each(xhr.responseJSON.errors, function(key, value){
                    errorString += '<p>'+value+'</p>';
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    html: errorString
                })
                break;

            case 404:
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Non trouvée.',
                })
                break;

            case 419:
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jeton de sécurité invalide. Veuillez recharger la page.',
                    //si on clique sur le bouton OK, on recharge pour mettre à jour la page avec le bon csrf token
                }).then((result) => {
                    if(result.value){
                        window.location.reload(true);
                    }
                })
                break;

            default:
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur...',
                    text: 'Erreur. Cliquez pour recharger la page.',
                }).then((result) => {
                    if(result.value){
                        window.location.reload(true);
                    }
                })
                break;
        }
    }

})
