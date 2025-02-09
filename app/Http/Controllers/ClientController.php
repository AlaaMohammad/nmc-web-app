<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $clients = Client::all();
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255',
            'phone'                 => 'required|string|max:255',
            'address'               => 'required|string|max:255',
            'status'                => 'required|in:active,inactive',
            'operation_hours_from'  => 'required|date_format:H:i',
            'operation_hours_to'    => 'required|date_format:H:i',
            'logo_url'              => 'required|url',
        ]);

        Client::create($validatedData);

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
        // Validate the incoming data
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'phone'                 => 'nullable|string|max:255',
            'address'               => 'nullable|string|max:255',
            'status'                => 'required|in:active,inactive',
            'operation_hours_from'  => 'nullable|date_format:H:i',
            'operation_hours_to'    => 'nullable|date_format:H:i',
            'logo_url'              => 'nullable|url',
        ]);

        $client->update($validatedData);

        // Update client with validated data
        $client->update($validatedData);

        // Redirect to the clients index with a success message
        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
        $client->delete();
        return redirect()->route('clients.index');
    }

    public function activateAccount(Client $client)
    {
        $client->status = 'active';
        $client->save();
        return redirect()->route('clients.index');
    }

    public function deactivateAccount(Client $client)
    {
        $client->status = 'inactive';
        $client->save();
        return redirect()->route('clients.index');
    }
}
