var $datatable;
const url = window.location.origin;
$(".flagShowDiv").hide();
$(document).attr("title", "Company");
document.addEventListener("DOMContentLoaded", function() {
    // Datatables clients
    $datatable = $("#datatables-company").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ordering: false,
        ajax: "/companies",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { className: 'thisrow', data: 'logo', name: 'logo' },
            { className: 'thisrow', data: 'name', name: 'name' },
            { className: 'thisrow', data: 'email', email: 'email' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$(document).on("click", ".edit_company", function() {
    $(".flagShowDiv").show();
    var id = $(this).data("cid");
    $("#addCompanyModal").modal("show");
    $(".modal-title_edit").text("Edit Company");
    $.ajax({
        url: '/companies/' + id,
        type: 'get',
        data: {},
        processData: false,
        contentType: false,
        success: function(response) {

            $(".company").val(response.data.company.name);
            $(".email").val(response.data.company.email);
            $(".logo").attr("src", '/storage/' + response.data.company.logo);
            $('#companyId').val(response.data.company.id);
        },

        error: function(ts) {}
    });

    $(".saveButton").text("Update");
    $("#addCompanyModal").modal("show");
    $('#addCompanyModal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    })
});

$(document).on("click", ".addCompany", function() {
    $(".modal-title_edit").text("Add Company");
    $(".flagShowDiv").hide();
    $(".saveButton").text("Save");
    $("#addCompanyModal").modal("show");

});
$('#company_form').submit(function(e) {
    e.preventDefault();
    alert("sdjd")
    let formData = new FormData(this);
    $('#file-input-error').text('');
    $.ajax({
        type: 'POST',
        url: url+"/companies",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                toastr.success(data.message);
                // location.reload();
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
            } else {
                toastr.error(data.error);
            }
        }
    });
});


$(document).on("click", ".delete_company_button", function() {
    var id = $(this).data("id");
    $(".modal-title_edit").text("Delete Company");
    $(".id").val(id);
    $("#deleteCompanyModal").modal("show");

});
$(".deleteCompanyButton").click(function(e) {
    e.preventDefault();
    var id = $('.id').val();
    $.ajax({
        type: 'DELETE',
        url: "/companies/" + id,
        data: { id: id },
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                toastr.success(data.message);
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
                var oTable = $('#datatables-company').dataTable();
                oTable.fnDraw(false);
                $("#addtestimonialsModal").modal("hide");
            } else {
                toastr.success(data.error);
            }
        }
    });

});

$('#deleteCompanyModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})
$('#addCompanyModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})