$('.modal').modal();

$(function () {
   $('.error').each(function () {
      M.toast({html: $(this).data('msg'), displayLength: 5000});
   });
});


function editAccountPhoto(id) {
    $.ajax({
        url: '/edit',
        data: 'photo_edit=' + id,
        type: "POST",
        success:function (data) {
            $('.form-div').html(data.view);
            $('.form-div label').addClass('active');
            $('#modalEditPhoto').modal('open');
        }
    });
}

function registerAccountPhoto(id) {
    $.ajax({
        url: '/',
        data: 'photo_register=' + id,
        type: "POST",
        success: function (data) {
            $('.form-div').html(data.view);
            $('#modalRegister').modal('open');
        }
    });
}

function validateAccountPhoto(id) {
    $.ajax({
        url: '/validate',
        data: 'photo_validate=' + id,
        type: "POST",
        success: function (data) {
            $('.form-div').html(data.view);
            $('#modalRegister').modal('open');
            $('.form-div form').submit(function (e) {
                e.stopPropagation();
                e.preventDefault();

                let formData = new FormData(this);

                let loader = $('#loader');
                loader.find('.message').text('Please wait, validating account...');
                loader.modal({
                    dismissible: false
                });
                loader.modal('open');

                $('#modalRegister').modal('close');

                let progress = loader.find('.progress div').addClass('determinate').removeClass('indeterminate').css('width', '0%');
                let val = 0;
                let interv = setInterval(function () {
                    if (val === 101) {
                        clearInterval(interv);
                    } else {
                        progress.css('width', val + '%');
                    }
                    val++;
                }, 60);

                $.ajax({
                    url: '/validate',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        if (data.isValid) M.toast({html: 'Successfully validated!'});
                        else M.toast({html: 'Submitted image does not match!'});
                        loader.modal('close');
                        progress.addClass('indeterminate').removeClass('determinate').css('width', 'unset');

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        }
    });
}

function deleteAccountPhoto(id) {
    $.ajax({
        url: '/',
        data: 'photo_delete=' + id,
        type: "POST",
        success: function () {
            location.reload(true);
        }
    });
}

function toggleAccountPhoto(id, value) {
    $.ajax({
        url: '/',
        data: 'photo_toggle[id]=' + id + '&photo_toggle[value]=' + value,
        type: "POST",
        success: function (data) {
            M.toast({html: data.data})
        }
    });
}

$(':checkbox').change(function () {
   toggleAccountPhoto($(this).data('id'), !this.checked);
});