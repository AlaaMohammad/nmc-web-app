<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.technicians.index', [
            'technicians' => Technician::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.technicians.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $technician = new Technician();
        $technician->name = $request->name;
        $technician->email = $request->email;
        $technician->phone = $request->phone;
        $technician->address = $request->address;
        $technician->save();
        return redirect()->route('technicians.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
        //
        return view('admin.technicians.show', compact('technician'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technician $technician)
    {
        //
        return view('admin.technicians.edit', compact('technician'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technician $technician)
    {
        //
        $technician->full_name = $request->full_name;
        $technician->email = $request->email;
        $technician->phone = $request->phone;
        $technician->address = $request->address;
        $technician->save();
        return redirect()->route('technicians.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        //
        $technician->delete();
        return redirect()->route('technicians.index');
    }

    public function activateAccount(Technician $technician)
    {

        $technician->status = 'active';
        $technician->save();
        return redirect()->back();
    }

    public function deactivateAccount(Technician $technician)
    {
        $technician->status = 'inactive';
        $technician->save();
        return redirect()->back();
    }
}
