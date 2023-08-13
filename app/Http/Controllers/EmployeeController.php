<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        $data['companies']=Company::get();
        if ($request->ajax()) {
            $data = Employee::select('*')->with('company')->orderBy('created_at','DESC');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('company',function($row){
                        return $row->company->name?? '';
                    })
                    ->addColumn('action', function($row){
                            $btn=' <a class="btn btn-primary btn-sm edit_employee m-1" data-cid="'.$row->id.'" data-bs-target="#addEmployeeModal" title="edit" data-bs-toggle="tooltip"> <i class="fas fa-fw fa-edit"></i></a>';
                            $btn.='<a class="btn btn-primary btn-sm delete_employee_button" data-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal" title="Delete" data-bs-toggle="tooltip"> <i class="fas fa-fw fa-trash"></i></a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('employee',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            if($request->employeeId){
                $employee=Employee::find($request->employeeId);  
                $msg="Employee Updated Successfully!";
  
            }else{
              $employee = new Employee;
              $msg='Employee Created Successfully';
            }
                 
                  $employee->first_name = $request->input('fname'); 
                  $employee->last_name = $request->input('lname'); 
                  $employee->email = $request->input('email'); 
                  $employee->phone = $request->input('phone'); 
                  $employee->company_id = $request->input('company');                   
                  $employee->save();
                  return response()->json(['message'=>$msg]);
              } catch (\Illuminate\Database\QueryException $e) {
                      return response(['success' => false,'error' =>$e->getMessage()]);
              }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json(['data' => ['employee' => $employee]]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try{
            $employee->delete();
             return response()->json(['message' => 'Employee deleted successfully']);
         }catch (\Illuminate\Database\QueryException $e) {
             return response(['success' => false,'error' =>'Something Went Wrong']);
         }
    }
}
