<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Answer;
use App\Models\Option;
use App\Models\User;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $results = User::whereHas('answers')
                ->withSum('answers', 'points')
                ->get();
        } else {
            $results = $user->loadSum('answers', 'points');
        }

        return view ('diagnostic.index', compact('results', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $diagnostics = Diagnostic::with('options')->get();
        $alreadyAnswered = Answer::where('user_id', $user->id)->exists();

        if ($alreadyAnswered && $user->role === 'user') {
            return redirect()->route('diagnostic.index')->withErrors(['Você já respondeu esse diagnóstico.']);
        }

        return view ('diagnostic.create', compact('diagnostics', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        foreach ($request->input('answers') as $diagnosticId => $optionId) {
            $option = Option::find($optionId);

            Answer::create([
                'user_id'       => $user->id,
                'diagnostic_id' => $diagnosticId,
                'option_id'     => $optionId,
                'points'        => $option->points
            ]);
        }

        return redirect()->route('diagnostic.index')->with('success', 'Respostas enviadas com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnostic $diagnostic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnostic $diagnostic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diagnostic $diagnostic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnostic $diagnostic)
    {
        //
    }
}
