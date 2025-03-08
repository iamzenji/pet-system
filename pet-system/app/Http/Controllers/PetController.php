<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class PetController extends Controller
{
    public function index(Request $request)
    {
        return view('pets.index');
    }


    public function getPet(Request $request)
    {
        $query = Pet::query();

        // return $pets = ;
        return DataTables::make($query->get())->toJson();
    }

    // todo: alignment and add try catch
    public function store(Request $request)
    {
        try {
            $request->validate([
                'type'   => 'required',
                'breed'  => 'required',
                'gender' => 'required',
                'color'  => 'required',
                'size'   => 'required',
                'age'    => 'required|numeric',
                'weight' => 'required|numeric',
                'image'  => 'nullable|image|max:2048',
            ]);
            $pet         = new Pet();
            $pet->type   = $request->type;
            $pet->breed  = $request->breed;
            $pet->gender = $request->gender;
            $pet->color  = $request->color;
            $pet->size   = $request->size;
            $pet->age    = $request->age;
            $pet->weight = $request->weight;

            if ($request->hasFile('image')) {
                $imagePath  = $request->file('image')->store('pets', 'public');
                $pet->image = $imagePath;
            }

            $pet->save();

            return response()->json([
                'success' => true,
                'message' => 'Pet added successfully!',
                'data'    => $pet
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the pet.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // todo: alignment and add try catch
    public function update(Request $request, $id)
    {
        try {
            $pet = Pet::findOrFail($id);

            $validatedData = $request->validate([
                'type'   => 'required|string|max:255',
                'breed'  => 'required|string|max:255',
                'gender' => 'required|string|max:10',
                'color'  => 'nullable|string|max:255',
                'size'   => 'nullable|string|max:255',
                'age'    => 'nullable|integer',
                'weight' => 'nullable|string|max:255',
                'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

              // image upload
            if ($request->hasFile('image')) {
                $imagePath              = $request->file('image')->store('pets', 'public');
                $validatedData['image'] = $imagePath;
            }

            $pet->update($validatedData);

            return response()->json(['success' => true, 'message' => 'Pet updated successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the pet.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Delete
    public function destroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json(['success' => false, 'message' => 'Pet not found'], 404);
        }

        try {
            $pet->delete();
            return response()->json(['success' => true, 'message' => 'Pet deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete pet!'], 500);
        }
    }

    // public function getTypes()
    // {
    //     $types = DB::table('types')->select('type')->distinct()->pluck('type');
    //     return response()->json($types);
    // }

    // public function getBreeds($type)
    // {
    //     $breeds = DB::table('types')->where('type', $type)->select('breed')->distinct()->pluck('breed');
    //     return response()->json($breeds);
    // }


}


