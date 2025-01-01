<?php

namespace App\Http\Controllers;


use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $status = Status::all(); // Fetch all categories
        return view('adminstatus', compact('status')); // Pass data to the view
    }

    public function create()
    {
        return view('status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $status = strtolower($request->input('status'));

        Status::create(['status' => $status]);

        return redirect()->route('adminstatus');
    }

    public function show(Status $status)
    {
        return view('status.show', compact('status$status'));
    }

    public function edit($id)
    {
        $status = Status::findOrFail($id); // Find the category or return a 404
        return view('updatestatus', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $status = Status::findOrFail($id); // Find the category or return a 404
        $status->status = strtolower($request->input('status'));
        $status->save();

        return redirect()->route('adminstatus');
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id); // Find by id or throw 404 if not found
        $status->delete();

        return redirect()->route('adminstatus')->with('success', 'Status berhasil dihapus.');
    }
}
