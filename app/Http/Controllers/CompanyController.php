<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::select('*')->orderBy('created_at','DESC');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                            $btn=' <a class="btn btn-primary btn-sm edit_company m-1" data-cid="'.$row->id.'" data-bs-target="#addCompanyModal" title="edit" data-bs-toggle="tooltip"> <i class="fas fa-fw fa-edit"></i></a>';
                            $btn.='<a class="btn btn-primary btn-sm delete_company_button" data-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal" title="Delete" data-bs-toggle="tooltip"> <i class="fas fa-fw fa-trash"></i></a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('company');
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
          if($request->companyId){
              $company=Company::find($request->companyId);

              $msg="Company Updated Successfully!";

          }else{
            $company = new Company;
            $msg='Company created successfully';
          }
               
                $company->name = $request->input('company_name'); 
                $company->email = $request->input('company_email'); 
                
                if ($request->hasFile('logo')) { 
                    $logo = $request->file('logo');
                    $imagename = time() . rand() . '.' . $logo->getClientOriginalExtension();
                    $logo->move(public_path('/uploads/logo'), $imagename);
                    $company->logo = '/uploads/logo/' . $imagename;
                }
                
                $company->save();
                return response()->json(['message'=>$msg]);
            } catch (\Illuminate\Database\QueryException $e) {
                    return response(['success' => false,'error' =>'Something Went Wrong']);
            }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return response()->json(['data' => ['company' => $company]]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        try{
           $company->delete();
            return response()->json(['message' => 'Company deleted successfully']);
        }catch (\Illuminate\Database\QueryException $e) {
            return response(['success' => false,'error' =>'Something Went Wrong']);
        }
    }
}
