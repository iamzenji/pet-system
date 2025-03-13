<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption; // Assuming you have an Adoption model
use App\Models\Pet;
use Yajra\DataTables\Facades\DataTables;


class AdoptionController extends Controller
{
    // PUBLIC VIEW PET
    public function showAvailablePets()
    {
        $pets  = Pet::all();
        return view('list', compact('pets'));
    }

    // STORAGE FORM PUBLIC VIEW
    public function store(Request $request)
    {
        $request->validate([
            'pet_id'     => 'nullable|exists:pets,id',
            'name'       => 'required|string',
            'email'      => 'required|email',
            'contact'    => 'required|digits_between:10,15',
            'address'    => 'required|string',
            'reason'     => 'required|string',
            'experience' => 'required|string',
        ]);
        Adoption::create([
            'pet_id'     => $request->input('pet_id'),
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'contact'    => $request->input('contact'),
            'address'    => $request->input('address'),
            'reason'     => $request->input('reason'),
            'experience' => $request->input('experience'),
        ]);

        return response()->json(['message' => 'Adoption request submitted successfully!']);
    }


    // USER SYSTEM VIEW

    public function index()
    {
        return view('pets.adopt-pet');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Adoption::select(['id', 'pet_id', 'name', 'email', 'contact', 'address', 'reason', 'experience', 'status']);

            return DataTables::of($data)
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-info btn-sm view-btn"
                                data-name="' . e($row->name) . '"
                                data-email="' . e($row->email) . '"
                                data-contact="' . e($row->contact) . '"
                                data-address="' . e($row->address) . '"
                                data-reason="' . e($row->reason) . '"
                                data-experience="' . e($row->experience) . '"
                                data-status="' . e($row->status) . '">
                                <i class="fa fa-eye"></i> View
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                                <i class="fa fa-trash"></i> Delete
                            </button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function destroy($id)
    {
        Adoption::findOrFail($id)->delete();
        return response()->json(['message' => 'Adoption request deleted']);
    }

public function updateStatus(Request $request, $id)
{
    $adoption = Adoption::findOrFail($id);
    $adoption->status = $request->status;
    $adoption->save();

    return response()->json(['success' => 'Status updated successfully.']);
}


}
