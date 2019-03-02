$('.modal').modal();

function editAccountPhoto(id) {
    $.ajax({
        url: '/',
        data: 'photo_edit=' + id,
        type: "POST",
        success:function (data) {
            $('.form-div').html(data.view);
            $('#modalEditPhoto').modal('open');
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