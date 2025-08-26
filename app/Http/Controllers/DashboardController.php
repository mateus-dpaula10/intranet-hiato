<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Dashboard;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $users = User::all();
        $feedbacks = collect();
        $now = Carbon::now();

        foreach ($users as $user) {
            if (!$user->admission_date) continue;

            $admission = Carbon::parse($user->admission_date);

            $feedbackPeriods = [
                '3 meses' => $admission->copy()->addMonths(3),
                '6 meses' => $admission->copy()->addMonths(6),
                '1 ano'   => $admission->copy()->addYear()
            ];

            $futureDates = collect($feedbackPeriods)->filter(fn($date) => $date->gte($now));

            if ($futureDates->isNotEmpty()) {
                $nextDate = $futureDates->sort()->first();
                $ruleName = collect($feedbackPeriods)->search($nextDate);
            } else {
                $lastAnnual = $admission->copy()->addYear();
                while ($lastAnnual->lt($now)) {
                    $lastAnnual->addYear();
                }
                $nextDate = $lastAnnual;
                $ruleName = $admission->diffInYears($nextDate) . ' anos';
            }

            $daysLeft = ceil($now->diffInDays($nextDate, false));

            $feedbacks->push([
                'user' => $user,
                'date' => $nextDate,
                'days_left' => $daysLeft,
                'rule' => $ruleName
            ]);
        }

        if ($authUser->role !== 'admin') {
            $feedbacks = $feedbacks->filter(fn($f) => $f['user']->id === $authUser->id);
        }

        $feedbacks = $feedbacks->sortBy('date')->values();

        return view ('dashboard.index', compact('authUser', 'vacations', 'feedbacks'));
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
