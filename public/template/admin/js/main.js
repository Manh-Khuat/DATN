$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function removeRow(id, url)
{
    if (confirm('Xóa không thể khôi phục. Bạn có chắc ?')) {
        $.ajax({
            type: 'DELETE',
            datatype: 'JSON',
            data: {id},
            url: url,
            success: function (result) {
                if (result.error === false) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Xóa không thành công. Vui lòng thử lại');
                }
            }
        })
    }
}

function updateRow(id, url)
{
        $.ajax({
            type: 'POST',
            datatype: 'JSON',
            data: {id},
            url: url,
            success: function (result) {
                if (result.error === false) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Cập nhật không thành công. Vui lòng thử lại');
                }
            }
        })
}

//Upload File

$('#upload').change(function () {
    const form = new FormData();
    form.append('file', $(this)[0].files[0]);

    $.ajax({
        processData: false,
        contentType: false,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        datatype: 'JSON',
        data: form,
        url: '/admin/upload/services',
        success: function (results) {
            if (results.error === false) {
                $('#image_show').html('<a href="' + results.url + '" target="_blank">' +
                    '<img src="' + results.url + '" width="100px"></a>');
                $('#thumb').val(results.url);
            } else {
                alert('Upload File Error')
            }
        }

    })
})


