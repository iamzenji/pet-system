<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class TypeController extends Controller
{
    // ROUTE TO MANAGE PAGE
    public function index()
    {
        return view('pets.manage');
    }

    // FETCH THE DATA ON DATA TABLES
    public function list()
    {
        return FacadesDataTables::of(Type::select('id', 'type', 'breed'))->make(true);
    }

    // STORE PET TYPES AND BREEDS
    public function store(Request $request)
    {
        $request->validate(['type' => 'required', 'breed' => 'required']);
        Type::create($request->all());
        return response()->json(['success' => 'Type added successfully!']);
    }

    // DELETE PET TYPES AND BREEDS
    public function destroy($id)
    {
        Type::find($id)->delete();
        return response()->json(['success' => 'Type deleted successfully!']);
    }

    // UPDATE PET TYPES AND BREEDS
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

    // FETCH THE LIST TYPES ON SELECT MODAL FORM
    public function fetchTypes()
    {
        $types = Type::distinct()->get(['type']);
        return response()->json($types);
    }

      // Fetch breeds based on selected type
    public function fetchBreeds(Request $request)
    {
        $breeds = Type::where('type', $request->type)->pluck('breed');
        return  response()->json($breeds);
    }
}
