<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    public function index(Request $request)
    {
        return view('pets.index');
    }

    public function getPet(Request $request)
{
    $pets = Pet::all()->map(function ($pet) {
        $pet->unique_id = strtoupper(
            substr($pet->type, 0, 1) .
            substr($pet->breed, 0, 1) .
            substr($pet->gender, 0, 1) . "-" .
            substr($pet->color, 0, 1) .
            substr($pet->size, 0, 1) .
            $pet->age . "-" .
            $pet->id
        );
        return $pet;
    });

    return DataTables::of($pets)->toJson();
}

      // ADD PET
    public function store(Request $request)
    {
        $request->validate([
            'type'               => 'required|string',
            'breed'              => 'required|string',
            'gender'             => 'required|string',
            'color'              => 'required|string',
            'size'               => 'required|string',
            'age'                => 'required|integer',
            'weight'             => 'required|numeric',
            'health_status'      => 'required|string',
            'spayed_neutered'    => 'required|string',
            'vaccination_status' => 'required|string',
            'good_with'          => 'required|string',
            'adoption_status'    => 'required|string',
            'temperament'        => 'required|string',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pets_images', 'public');
        } else {
            $imagePath = null;
        }

        Pet::create([
            'type'               => $request->type,
            'breed'              => $request->breed,
            'gender'             => $request->gender,
            'color'              => $request->color,
            'size'               => $request->size,
            'age'                => $request->age,
            'weight'             => $request->weight,
            'health_status'      => $request->health_status,
            'spayed_neutered'    => $request->spayed_neutered,
            'vaccination_status' => $request->vaccination_status,
            'good_with'          => $request->good_with,
            'adoption_status'    => $request->adoption_status,
            'temperament'        => $request->temperament,
            'image'              => $imagePath,
        ]);

        return response()->json(['message' => 'Pet added successfully!'], 200);
    }

      // UPDATE PET
    public function update(Request $request, $id)
    {
        try {
            $pet = Pet::findOrFail($id);

            $validatedData = $request->validate([
                'type'               => 'required|string|max:255',
                'breed'              => 'required|string|max:255',
                'gender'             => 'required|string|max:10',
                'color'              => 'nullable|string|max:255',
                'size'               => 'nullable|string|max:255',
                'age'                => 'nullable|integer',
                'weight'             => 'nullable|numeric',
                'temperament'        => 'nullable|string|max:255',
                'health_status'      => 'nullable|string|max:255',
                'spayed_neutered'    => 'nullable|string|max:255',
                'vaccination_status' => 'nullable|string|max:255',
                'good_with'          => 'nullable|string|max:255',
                'adoption_status'    => 'nullable|string|max:255',
                'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $imagePath              = $request->file('image')->store('pets', 'public');
                $validatedData['image'] = $imagePath;
            }

            $pet->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Pet updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the pet.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
      // DESTROY PET
    public function destroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found'
            ], 404);
        }

        try {
            $pet->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pet deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete pet!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


}
