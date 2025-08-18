<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Answer;
use App\Models\Option;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $diagnostics = Diagnostic::with('options')->get();
        $alreadyAnswered = Answer::where('user_id', $user->id)->exists();

        if ($alreadyAnswered && $user->role === 'user') {
            return redirect()->route('dashboard.index')->withErrors(['Você já respondeu esse diagnóstico.']);
        }

        return view ('diagnostic.index', compact('diagnostics', 'user'));
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

        return redirect()->route('dashboard.index')->with('success', 'Respostas enviadas com sucesso.');
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
