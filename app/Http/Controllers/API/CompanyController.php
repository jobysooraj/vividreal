<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB; 

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $base_url = url('/');
        $companies = Company::select('id', 'name', DB::raw('CONCAT("' . $base_url . '", logo) AS logo'))->get();
        if ($companies->count() > 0) {
            return response()->json(['status' => true, 'data' => $companies, 'message' => 'Success']);
        } else {
            return response()->json(['status' => false, 'data' => [], 'message' => 'No data found']);
        }


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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
