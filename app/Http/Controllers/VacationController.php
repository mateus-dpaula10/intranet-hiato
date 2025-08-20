<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Vacation;

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

        $conflict = Vacation::where('user_id', '!=', $request->user_id)
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
            return back()->withErrors(['start_date' => 'Já existe outro colaborador de férias neste período.'])->withInput();
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
            return back()->withErrors([
                'start_date' => 'Já existe outro colaborador de férias neste período.'
            ])->withInput();
        }

        $vacation->update($request->only('user_id', 'start_date', 'end_date'));

        return redirect()->route('vacation.index')->with('success', 'Período de férias atualizado com sucesso.');
    }
}
