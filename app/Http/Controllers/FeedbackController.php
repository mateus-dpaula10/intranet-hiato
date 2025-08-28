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

        // $query = Feedback::select('feedbacks.*')
        //     ->join('users', 'users.id', '=', 'feedbacks.user_id')
        //     ->with('user');
        $query = Feedback::with('user');

        // if ($authUser->role !== 'admin') {
        //     $query->where('feedbacks.user_id', $authUser->id);
        // }
        if ($authUser->role !== 'admin') {
            $query->where('user_id', $authUser->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }   

        // $feedbacks = $query->orderBy('users.name')->get();
        $feedbacks = $query->get();

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
            'description'       => 'required|array|min:1',
            'description.*'     => 'required|string'
        ]);

        $userName = User::find($validated['user_id'])->name;

        Feedback::create([
            'user_id'          => $validated['user_id'],
            'completion_dates' => $validated['completion_date'],
            'descriptions'     => $validated['description']
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
            'description'       => 'required|array|min:1',
            'description.*'     => 'required|string'
        ]);

        $feedback->update([
            'user_id'          => $validated['user_id'],
            'completion_dates' => $validated['completion_date'],
            'descriptions'     => $validated['description']
        ]);

        $feedback->load('user');

        return redirect()->route('feedback.index')->with('success', "Feedback do colaborador '{$feedback->user->name}' atualizado com sucesso.");
    }

    public function destroy(Feedback $feedback) {
        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', "Feedback do colaborador '{$feedback->user->name}' excluído com sucesso.");
    }
}
