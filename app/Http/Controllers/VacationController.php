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

        Vacation::create($request->only('user_id', 'start_date', 'end_date'));

        return redirect()->route('vacation.index')->with('success', 'Período de férias cadastrado com sucesso.');
    }
}
