<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscQuestion;
use App\Models\DiscAnswer;

class DiscController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();

        $lastAnswer = DiscAnswer::where('user_id', $authUser->id)
                    ->latest()
                    ->first();
        $hashAnswered = $lastAnswer ? true : false;

        $totals = $lastAnswer->totals ?? ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $profile = $lastAnswer->profile ?? '';

        $discProfiles = [
            'D' => ['name' => 'Executor', 'description' => 'Pessoa orientada para ação, resultado, desafio, competitiva.'],
            'I' => ['name' => 'Comunicador', 'description' => 'Pessoa sociável, expressiva, entusiasta, motivadora.'],
            'S' => ['name' => 'Planejador', 'description' => 'Pessoa cooperativa, paciente, confiável, que gosta de rotina e harmonia.'],
            'C' => ['name' => 'Analista', 'description' => 'Pessoa detalhista, organizada, criteriosa, que segue regras e padrões.'],
        ];

        return view('disc.index', compact('authUser', 'hashAnswered', 'totals', 'profile', 'discProfiles'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $discs = DiscQuestion::all();

        return view ('disc.create', compact('authUser', 'discs'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        $scores = $request->input('scores');

        $totals = [
            'D' => 0,
            'I' => 0,
            'S' => 0,
            'C' => 0
        ];

        foreach ($scores as $line) {
            foreach ($line as $dimension => $value) {
                $totals[$dimension] += 5 - (int)$value;
            }
        }

        $totalsCopy = $totals;
        arsort($totalsCopy);
        $keys = array_keys($totalsCopy);
        $main = $keys[0];
        $secondary = $keys[1];
        $profile = $main . $secondary;

        DiscAnswer::create([
            'user_id' => $authUser->id,
            'scores'  => $scores,
            'totals'  => $totals,
            'profile' => $profile
        ]);

        return redirect()->route('disc.index')->with([
            'totals'  => $totals,
            'profile' => $profile
        ]);
    }
}
