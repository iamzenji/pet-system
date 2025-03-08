<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class TypeController extends Controller
{
    public function index()
    {
        return view('pets.manage');
    }

    public function list()
    {
        return FacadesDataTables::of(Type::select('id', 'type', 'breed'))->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['type' => 'required', 'breed' => 'required']);
        Type::create($request->all());
        return response()->json(['success' => 'Type added successfully!']);
    }

    public function destroy($id)
    {
        Type::find($id)->delete();
        return response()->json(['success' => 'Type deleted successfully!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type'  => 'required',
            'breed' => 'required'
        ]);

        $type = Type::findOrFail($id);
        $type->update($request->all());

        return response()->json(['success' => 'Type updated successfully!']);
    }

      // Fetch distinct types (e.g., Dog, Cat, etc.)
    public function fetchTypes()
    {
        $types = Type::distinct()->get(['type']);  // Fetch distinct types (no duplicates)
        return response()->json($types);           // Return as JSON response
    }

      // Fetch breeds based on selected type
    public function fetchBreeds(Request $request)
    {
        $breeds = Type::where('type', $request->type)->pluck('breed');  // Get all breeds related to the selected type
        return  response()->json($breeds);                              // Return breeds as JSON response
    }
}
