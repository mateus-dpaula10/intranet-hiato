<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index(Request $request) {
        $authUser = auth()->user();

        $query = Feedback::with('user');
        
        if ($authUser->role !== 'admin') {
            $query->where('user_id', $authUser->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }   
        
        $feedbacks = $query->get();

        if ($authUser->role !== 'admin') {
            $feedbacks->transform(function ($feedback) {
                $filteredDates = [];
                $filteredTypes = [];
                $filteredDescriptions = [];
                $filteredVisibles = [];

                foreach ($feedback->completion_dates as $i => $date) {
                    if ($feedback->visibles[$i] ?? false) {
                        $filteredDates[]        = $date;
                        $filteredTypes[]        = $feedback->types[$i] ?? null;
                        $filteredDescriptions[] = $feedback->descriptions[$i] ?? null;
                        $filteredVisibles[]     = true;
                    }
                }

                $feedback->completion_dates = $filteredDates;
                $feedback->types            = $filteredTypes;
                $feedback->descriptions     = $filteredDescriptions;
                $feedback->visibles         = $filteredVisibles;

                return $feedback;
            });
        }

        return view ('feedback.index', compact('authUser', 'feedbacks'));
    }

    public function create() {
        $users = User::orderBy('name', 'asc')->get();

        return view ('feedback.create', compact('users'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'completion_date'   => 'required|array|min:1',
            'completion_date.*' => 'required|date',
            'type'              => 'required|array|min:1',
            'type.*'            => 'required|string',
            'description'       => 'required|array|min:1',
            'description.*'     => 'required|string',
            'visible'           => 'array'
        ]);

        $userName = User::find($validated['user_id'])->name;

        Feedback::create([
            'user_id'          => $validated['user_id'],
            'completion_dates' => $validated['completion_date'],
            'types'            => $validated['type'],
            'descriptions'     => $validated['description'],
            'visibles'         => array_map(fn($i) =>
                isset($validated['visible'][$i]) ? true : false,
                array_keys($validated['completion_date'])
            )
        ]);

        return redirect()->route('feedback.index')->with('success', "Feedback(s) para o colaborador '{$userName}' cadastrado(s) com sucesso.");
    }

    public function edit(Feedback $feedback) {
        $users = User::where('role', 'collaborator')->orderBy('name', 'asc')->get();

        return view ('feedback.edit', compact('users', 'feedback'));
    }

    public function update(Request $request, Feedback $feedback) {
        $validated = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'completion_date'   => 'required|array|min:1',
            'completion_date.*' => 'required|date',
            'type'              => 'required|array|min:1',
            'type.*'            => 'required|string',
            'description'       => 'required|array|min:1',
            'description.*'     => 'required|string',
            'visible'           => 'array'
        ]);

        $visibles = array_map(fn($i) =>
            isset($validated['visible'][$i]) ? true : false,
            array_keys($validated['completion_date'])
        );

        $feedback->update([
            'user_id'          => $validated['user_id'],
            'completion_dates' => $validated['completion_date'],
            'types'            => $validated['type'],
            'descriptions'     => $validated['description'],
            'visibles'         => $visibles
        ]);

        $feedback->load('user');

        return redirect()->route('feedback.index')->with('success', "Feedback do colaborador '{$feedback->user->name}' atualizado com sucesso.");
    }

    public function destroy(Feedback $feedback) {
        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', "Feedback do colaborador '{$feedback->user->name}' excluído com sucesso.");
    }
}
