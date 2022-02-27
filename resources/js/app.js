require('./bootstrap');

import Alpine from 'alpinejs';

import Swal from 'sweetalert2'

window.Alpine = Alpine;

Alpine.start();

$(document).ready(function(){

    let ajaxForm = $('form.ajax-form');
    $(ajaxForm).each(function() {
       $(this).on('submit', (e)=> {
         e.preventDefault();
         let method = $(this).find('input[name="_method"]').val() || $(this).attr('method');
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
