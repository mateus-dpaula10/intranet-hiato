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

        if ($authUser->role === 'user' && !$authUser->answers()->exists()) {
            return redirect()->route('diagnostic.index');
        } elseif ($authUser->role === 'user' && $authUser->answers()->exists()) {
            return redirect()->route('dashboard.agradecimento');
        }

        $now = Carbon::now();

        $vacations = Vacation::with('user')
            ->when($authUser->role !== 'admin', function ($query) use ($authUser) {
                $query->where('user_id', $authUser->id);
            })
            ->get()
            ->flatMap(function ($vacation) use ($now) {
                return collect($vacation->periods ?? [])->map(function ($period, $index) use ($vacation, $now) {
                    $start = Carbon::parse($period['start_date']);
                    $end = Carbon::parse($period['end_date']);
                    $isRead = $period['is_read'] ?? false;

                    if ($start->gte($now) && !$isRead) {
                        return [
                            'vacation_id'  => $vacation->id,
                            'period_index' => $index,
                            'user'         => $vacation->user,
                            'start_date'   => $start,
                            'end_date'     => $end,
                            'is_read'      => $isRead
                        ];
                    }
                    return null;
                })->filter();
            })
            ->sortBy('start_date')
            ->values();

        $users = User::all();
        $feedbacks = collect();

        foreach ($users as $user) {
            if (!$user->admission_date) continue;

            $admission = Carbon::parse($user->admission_date);

            $feedbackPeriods = [
                '1 mês'   => $admission->copy()->addMonth(),
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
    
    public function agradecimento() 
    {
        return view ('dashboard.agradecimento');
    }
}
