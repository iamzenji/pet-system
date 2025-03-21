<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\Pet;
use Yajra\DataTables\Facades\DataTables;


class AdoptionController extends Controller
{
      // PUBLIC VIEW PET
    public function showAvailablePets()
    {
        $pets = Pet::all();
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

      // LIST OF REQUEST
    public function list()
{
    $adoptions = Adoption::with('pet')->select('adoptions.*');

    return DataTables::of($adoptions)
        ->addColumn('pet_details', function ($adoption) {
            if ($adoption->pet) {
                $pet = $adoption->pet;

                $unique_id = strtoupper(
                    substr($pet->type, 0, 1) .
                    substr($pet->breed, 0, 1) .
                    substr($pet->gender, 0, 1) .
                    "-" .
                    substr($pet->color, 0, 1) .
                    substr($pet->size, 0, 1) .
                    $pet->age .
                    "-" .
                    $pet->id
                );

                return "{$pet->name} {$unique_id}";
            }
            return 'No Pet Assigned';
        })
        ->addColumn('actions', function ($adoption) {
            return '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $adoption->id . '">Delete</button>';
        })
        ->rawColumns(['actions'])
        ->make(true);
}

      // DELETE
    public function destroy($id)
    {
        Adoption::findOrFail($id)->delete();
        return response()->json(['message' => 'Adoption request deleted']);
    }

      // UPDATE STATUS
    public function updateStatus(Request $request, $id)
    {
        $adoption         = Adoption::findOrFail($id);
        $adoption->status = $request->status;
        $adoption->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }

      // UPDATE DATE
    public function updateAdoptedDate(Request $request, $id)
    {
        $request->validate([
            'adopted_date' => 'required|date',
        ]);

        $adoption               = Adoption::findOrFail($id);
        $adoption->adopted_date = $request->adopted_date;
        $adoption->save();

        return response()->json(['message' => 'Adopted date updated successfully!']);
    }



      // REALTIME BAR GRAPH
    public function getAdoptionChartData($year = null)
    {
        $year = $year ?? date('Y');

        $adoptionData = Adoption::selectRaw('MONTH(adopted_date) as month, COUNT(*) as total')
            ->whereYear('adopted_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        $adoptionCounts = array_fill(0, 12, 0);

        foreach ($adoptionData as $data) {
            $adoptionCounts[$data->month - 1] = $data->total;
        }

        return response()->json([
            'months'    => $months,
            'adoptions' => $adoptionCounts
        ]);
    }

}
