<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;

class PetAnalyticsController extends Controller
{
    public function index()
    {
        return view('pets.analytics');
    }

    public function getAnalyticsData()
    {
        $petData = Pet::selectRaw('type, COUNT(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type')
                    ->toArray();

        return response()->json([
            'labels' => array_keys($petData),
            'data' => array_values($petData)
        ]);
    }

    public function getBreedAnalyticsData($type)
    {
        $breedData = Pet::where('type', $type)
                        ->selectRaw('breed, COUNT(*) as count')
                        ->groupBy('breed')
                        ->pluck('count', 'breed')
                        ->toArray();

        return response()->json([
            'labels' => array_keys($breedData),
            'data' => array_values($breedData),
            'type' => $type
        ]);
    }

    public function getUserAnalytics()
    {
        $roleNames = [
            2 => 'Administrator',
            3 => 'User',
            4 => 'Reader'
        ];

        $users = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereIn('role_user.role_id', array_keys($roleNames))
            ->select(DB::raw('COUNT(users.id) as count, role_user.role_id'))
            ->groupBy('role_user.role_id')
            ->get();

        $labels = $users->map(fn($user) => $roleNames[$user->role_id]);
        $data = $users->map(fn($user) => $user->count);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

}
