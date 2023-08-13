@extends('layouts.apps')
@section('content')
<div class="container-fluid">
    <div class="header">
        <h1 class="header-title">
            Employees
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Employees</a></li>

            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success addEmployees" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add new</button>
                </div>
                <div class="card-body">
                <table class="table table-bordered" id="datatables-employee">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
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
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="employee_form" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title_edit"> Add Employee Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="mb-3">
                        <label class="form-label">Employee First Name</label>
                        <input type="text" class="form-control femployee" name="fname" autocomplete="off" placeholder="Employee First name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Employee Last Name</label>
                        <input type="text" class="form-control lemployee" name="lname" autocomplete="off" placeholder="Employee Last Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> Email</label>
                        <input type="email" class="form-control email" name="email" autocomplete="off" placeholder="Email">
                    </div>
                     <div class="mb-3">
                        <label class="form-label"> Phone</label>
                        <input type="text" class="form-control phone" name="phone" autocomplete="off" placeholder="Phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> Company</label>
                    <select class="form-control company" name="company" id="company">
                    <option> Choose One</option>
                    @foreach($companies as $key => $company)
                         <option value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                   
                    </select>
                    </div>
                    <input type="hidden" name="employeeId" id="employeeId">
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
deleteEmployeeModal
@endslot
@slot('modaldialog')
modal-dialog60
@endslot
@slot('modaltitle')
Delete Employee
@endslot
@slot('modalbody')
<p>Do you want to Delete this Employee?</p>
<input type="hidden" name="id" class="id">
@endslot
@slot('modalfooter')
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<button type="button" class="btn btn-success deleteEmployeeButton">Delete</button>
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
    $(document).attr("title", "Employee");
$('#employee_form').submit(function(e) {
    e.preventDefault();
     var formData = new FormData(this);
    var id = $('#employeeId').val();
    $('#file-input-error').text('');
    $.ajax({
        type:'POST',
            url: "{{route('employees.store', ['employee' => ':id'])}}".replace(':id', id),
            data: formData,
            contentType: false,
            processData: false,
           
        success: function(data) {
             if ($.isEmptyObject(data.error)) {
                toastr.success(data.message);
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
                $("#addEmployeeModal").modal("hide");
            } else {
                toastr.error(data.error);
            }
        }
    });
});

});
$(document).ready(function() {
    const $datatable = $("#datatables-employee").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ordering: false,
        ajax: "{{ route('employees.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'company', name: 'company'},     
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});





$(document).on("click", ".edit_employee", function() {
    $(".flagShowDiv").show();
    var id = $(this).data("cid");
    $("#addEmployeeModal").modal("show");
    $(".modal-title_edit").text("Edit Employee");
    $.ajax({
        url: "{{route('employees.show', ['employee' => ':id'])}}".replace(':id', id), 
        type: 'get',
        data: {},
        processData: false,
        contentType: false,
        success: function(response) {
        const url = window.location.origin;
            $(".femployee").val(response.data.employee.first_name);
            $(".lemployee").val(response.data.employee.last_name);
            $(".email").val(response.data.employee.email);
            $(".phone").val(response.data.employee.phone);
             $(".company").val(response.data.employee.company_id);
          
            $('#employeeId').val(response.data.employee.id);
        },

        error: function(ts) {}
    });

    $(".saveButton").text("Update");
    $("#addEmployeeModal").modal("show");
    $('#addEmployeeModal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
    })
});

$(document).on("click", ".addEmployee", function() {
    $(".modal-title_edit").text("Add Employee");
    $(".flagShowDiv").hide();
    $(".saveButton").text("Save");
    $("#addEmployeeModal").modal("show");

});



$(document).on("click", ".delete_employee_button", function() {
    var id = $(this).data("id");
    $(".modal-title_edit").text("Delete Employee");
    $(".id").val(id);
    $("#deleteEmployeeModal").modal("show");

});
$(document).ready(function() {
$(".deleteEmployeeButton").click(function(e) {
    e.preventDefault();
    var id = $('.id').val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'DELETE',
        url: "{{route('employees.destroy', ['employee' => ':id'])}}".replace(':id', id), 
        data: { id: id,
         _token: csrfToken },
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                toastr.success(data.message);
                setTimeout(function() {
                    window.location.reload(1);
                }, 1000);
                $("#addEmployeeModal").modal("hide");
            } else {
                toastr.error(data.error);
            }
        }
    });

});
});

$('#deleteEmployeeModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})
$('#addEmployeeModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
})

</script>


