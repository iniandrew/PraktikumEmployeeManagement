<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';

        // RAW SQL QUERY
//        $employees = DB::select('
//            select *, employees.id as employee_id, positions.name as position_name
//            from employees
//            left join positions on employees.position_id = positions.id
//        ');

        // QUERY BUILDER
//        $employees = DB::table('employees')
//            ->select('*', 'employees.id as employee_id', 'positions.name as position_name')
//            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
//            ->get();

        // ELOQUENT
        $employees = Employee::all();

        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Employee';
        // RAW SQL Query
//        $positions = DB::select('select * from positions');

        // QUERY BUILDER
        // $positions = DB::table('positions')->get();

        // ELOQUENT
        $positions = Position::all();

        return view('employee.create', [
            'pageTitle' => $pageTitle,
            'positions' => $positions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');
        }


        // ELOQUENT
        $employee = new Employee();
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;


        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Employee Detail';

        // RAW SQL QUERY
//        $employee = collect(DB::select('
//            select *, employees.id as employee_id, positions.name as position_name
//            from employees
//            left join positions on employees.position_id = positions.id
//            where employees.id = ?
//        ', [$id]))->first();

        // QUERY BUILDER
//        $employee = DB::table('employees')
//            ->select('*', 'employees.id as employee_id', 'positions.name as position_name')
//            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
//            ->where('employees.id', $id)
//            ->first();

        // ELOQUENT
        $employee = Employee::find($id);

        return view('employee.show', [
           'pageTitle' => $pageTitle,
           'employee' => $employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('employee.edit', [
            'pageTitle' => 'Edit Employee',
            'positions' => Position::all(), // ELOQUENT
            'employee' => Employee::find($id) // ELOQUENT
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka',
            'position.required' => 'Pilih salah satu :attribute'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
            'position' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // UPDATE QUERY
//        DB::table('employees')
//            ->where('id', $id)
//            ->update([
//                'firstname' => $request->firstName,
//                'lastname' => $request->lastName,
//                'email' => $request->email,
//                'age' => $request->age,
//                'position_id' => $request->position,
//            ]);

        // ELOQUENT
        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();


        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       // QUERY BUILDER
//        DB::table('employees')
//            ->where('id', $id)
//            ->delete();

        // ELOQUENT
        Employee::find($id)->delete();

        return redirect()->route('employees.index');
    }


    public function downloadFile($employeeId)
    {
        $employee = Employee::find($employeeId);
        $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

        if(Storage::exists($encryptedFilename)) {
            return Storage::download($encryptedFilename, $downloadFilename);
        }
    }
}
