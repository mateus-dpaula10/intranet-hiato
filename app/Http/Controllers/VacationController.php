<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Vacation;
use Carbon\Carbon;

use Illuminate\Http\Request;

class VacationController extends Controller
{
    public function index()
    {
        $vacations = Vacation::with('user')->orderBy('start_date', 'asc')->get();

        return view ('vacation.index', compact('vacations'));
    }

    public function create()
    {
        $users = User::where('role', 'collaborator')->orderBy('name', 'asc')->get();

        return view ('vacation.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date'
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $daysRequested = $start->diffInDays($end) + 1;

        $vacationsLastYear = Vacation::where('user_id', $request->user_id)
            ->where('end_date', '>=', $start->copy()->subDays(365))
            ->get();

        $daysUsed = $vacationsLastYear->sum(function ($v) {
            return Carbon::parse($v->start_date)->diffInDays(Carbon::parse($v->end_date)) + 1;
        });

        $daysRemaining = 30 - $daysUsed;

        if ($daysRequested > $daysRemaining && $daysUsed > 0 && !$request->has('confirm')) {
            return back()->withInput()->with('warning', "O período selecionado ultrapassa o limite máximo de 30 dias de férias.");
        }

        if ($daysRequested > $daysRemaining && !$request->has('confirm')) {
            return back()->withInput()->with('warning', "Este colaborador já gozou {$daysUsed} dias de férias nos últimos 12 meses, restam apenas {$daysRemaining}. Deseja confirmar mesmo assim?");
        }

        $conflicts = Vacation::with('user')
            ->where('user_id', '!=', $request->user_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->get();

        if ($conflicts->isNotEmpty() && !$request->has('confirm')) {
            $names = $conflicts->map(function ($v) {
                $start = Carbon::parse($v->start_date)->format('d/m/Y');
                $end = Carbon::parse($v->end_date)->format('d/m/Y');
                return $v->user->name . " ({$start} até {$end})";
            })->join(', ');

            return back()->withInput()->with('warning', "Já existe(m) colaborador(es) de férias neste período: ${names}. Deseja confirmar mesmo assim?");
        }

        Vacation::create($request->only('user_id', 'start_date', 'end_date'));

        return redirect()->route('vacation.index')->with('success', 'Período de férias cadastrado com sucesso.');
    }

    public function edit(Vacation $vacation)
    {
        $users = User::where('role', 'collaborator')->orderBy('name', 'asc')->get();

        return view ('vacation.edit', compact(['users', 'vacation']));
    }

    public function update(Request $request, Vacation $vacation)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date'
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $daysRequested = $start->diffInDays($end) + 1;

        $vacationsLastYear = Vacation::where('user_id', $request->user_id)
            ->where('id', '!=', $vacation->id)
            ->where('end_date', '>=', $start->copy()->subDays(365))
            ->get();

        $daysUsed = $vacationsLastYear->sum(function ($v) {
            return Carbon::parse($v->start_date)->diffInDays(Carbon::parse($v->end_date)) + 1;
        });

        $totalDaysAfterUpdate = $daysUsed + $daysRequested;
        $daysRemaining = 30 - $daysUsed;

        if ($daysUsed === 0 && $daysRequested > 30 && !$request->has('confirm')) {
            return back()->withInput()->with('warning', "O período selecionado ultrapassa o limite máximo de 30 dias de férias.");
        }

        if ($totalDaysAfterUpdate > 30 && !$request->has('confirm')) {
            return back()->withInput()->with('warning', "Este colaborador já gozou {$daysUsed} dias de férias nos últimos 12 meses. Restam apenas {$daysRemaining} dias.");
        }

        $conflict = Vacation::where('id', '!=', $vacation->id) 
            ->where('user_id', '!=', $request->user_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->with('warning', 'Já existe outro colaborador de férias neste período.')->withInput();
        }

        $vacation->update($request->only('user_id', 'start_date', 'end_date'));

        return redirect()->route('vacation.index')->with('success', 'Período de férias atualizado com sucesso.');
    }

    public function destroy(Vacation $vacation)
    {
        $vacation->delete();

        return redirect()->back()->with('success', 'Período de férias do colaborador "' . $vacation->user->name . '" removido com sucesso.');
    }
}
