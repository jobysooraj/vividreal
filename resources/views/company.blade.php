@extends('layouts.apps')

@section('content')
<div class="container-fluid">
    <div class="header">
        <h1 class="header-title">
            Companies
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Companies</a></li>

            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success addCompanies" data-bs-toggle="modal" data-bs-target="#addCompanyModal">Add new</button>
                </div>
                <div class="card-body">
                <table class="table table-bordered" id="datatables-company">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Logo</th>
                            <th>Action</th>
                            <!-- Other columns -->
                        </tr>
                    </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="company_form" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title_edit"> Add Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control company" name="company_name" autocomplete="off" placeholder="Company">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Email</label>
                        <input type="email" class="form-control company_email" name="company_email" autocomplete="off" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo Upload</label>
                        <input type="file" class="form-control " placeholder="Company Logo" name="logo">
                    </div>
                    <div class="flagShowDiv">
                        @isset($comany)
                            @if($comany->logo)
                                <img src="{{asset($comany->logo) }}" class="logo" width="50px" height=""50px alt="logo">
                       
                        @endif
                        @endisset
                    </div>
                    <input type="hidden" name="companyId" id="companyId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success saveButton">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@component('components.modal')
@slot('modalid')
deleteCompanyModal
@endslot
@slot('modaldialog')
modal-dialog60
@endslot
@slot('modaltitle')
Delete Company
@endslot
@slot('modalbody')
<p>Do you want to Delete this Company?</p>
<input type="hidden" name="id" class="id">
@endslot
@slot('modalfooter')
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<button type="button" class="btn btn-success deleteCompanyButton">Delete</button>
@endslot
@endcomponent
@endsection

  

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



{{-- <script src="{{ asset('js/company.js') }}"></script> --}}
<script>
$(document).ready(function() {
    $(".flagShowDiv").hide();
    $(document).attr("title", "Company");
$('#company_form').submit(function(e) {
    e.preventDefault();
     var formData = new FormData(this);
    var id = $('#companyId').val();
    $('#file-input-error').text('');
    $.ajax({
        type:'POST',
            url: "{{route('companies.store', ['company' => ':id'])}}".replace(':id', id),
            data: formData,
            contentType: false,
            processData: false,
           
        success: function(data) {
             if ($.isEmptyObject(data.error)) {
                toastr.success(data.message);
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
                $("#addCompanyModal").modal("hide");
            } else {
                toastr.error(data.error);
            }
        }
    });
});

});
$(document).ready(function() {
    const $datatable = $("#datatables-company").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ordering: false,
        ajax: "{{ route('companies.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'logo', name: 'logo', render: function(data, type, full, meta) {
                    if (type === 'display') {
                        return '<img src="' + data + '" alt="Logo" width="50" height="50">';
                    }
                    return data;
                } },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});





$(document).on("click", ".edit_company", function() {
    $(".flagShowDiv").show();
    var id = $(this).data("cid");
    $("#addCompanyModal").modal("show");
    $(".modal-title_edit").text("Edit Company");
    $.ajax({
        url: "{{route('companies.show', ['company' => ':id'])}}".replace(':id', id), 
        type: 'get',
        data: {},
        processData: false,
        contentType: false,
        success: function(response) {
        const url = window.location.origin;
            $(".company").val(response.data.company.name);
            $(".company_email").val(response.data.company.email);
            console.log(response.data.company.logo);
            $(".logo").attr("src", url + response.data.company.logo);
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



$(document).on("click", ".delete_company_button", function() {
    var id = $(this).data("id");
    $(".modal-title_edit").text("Delete Company");
    $(".id").val(id);
    $("#deleteCompanyModal").modal("show");

});
$(document).ready(function() {
$(".deleteCompanyButton").click(function(e) {
    e.preventDefault();
    var id = $('.id').val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'DELETE',
        url: "{{route('companies.destroy', ['company' => ':id'])}}".replace(':id', id), 
        data: { id: id,
         _token: csrfToken },
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                toastr.success(data.message);
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
                $("#addCompanyModal").modal("hide");
            } else {
                toastr.error(data.error);
            }
        }
    });

});
});

$('#deleteCompanyModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})
$('#addCompanyModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})

</script>


