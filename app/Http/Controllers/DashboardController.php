<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Dashboard;
use App\Models\Vacation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = auth()->user();

        $vacations = collect();

        $vacations = Vacation::with('user')
            ->whereBetween('start_date', [now(), now()->addDays(30)])
            ->where('is_read', false)
            ->when($authUser->role !== 'admin', function ($query) use ($authUser) {
                $query->where('user_id', $authUser->id);
            })
            ->orderBy('start_date')
            ->get();

        // $feedbacks = Feedback

        return view ('dashboard.index', compact('authUser', 'vacations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
