<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Vacation;
use Carbon\Carbon;

use Illuminate\Http\Request;

class VacationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Vacation::with('user');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }   

        $vacations = $query->get();

        $vacations = $vacations->sortByDesc(function ($vacation) {
            if (!empty($vacation->periods)) {
                return $vacation->periods[0]['start_date'];
            }
            return null;
        });

        return view ('vacation.index', compact('vacations', 'user'));
    }

    public function create()
    {
        $users = User::orderBy('name', 'asc')->get();

        return view ('vacation.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'               => 'required|exists:users,id',
            'start_date'            => 'required|array|min:1',
            'start_date.*'          => 'required|date',
            'end_date'              => 'required|array|min:1',
            'end_date.*'            => 'required|date'
        ], [
            'start_date.*.required' => 'Data de início é obrigatória.',
            'end_date.*.required'   => 'Data de término é obrigatória.'
        ]);

        $user = User::findOrFail($validated['user_id']);
        $newPeriods = [];

        foreach ($validated['start_date'] as $i => $start) {
            $end = $validated['end_date'][$i] ?? null;
            if (!$end) continue;

            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);

            if ($endCarbon->lt($startCarbon)) {
                return back()->withInput()->with('warning', 
                    "A data final ({$endCarbon->format('d/m/Y')}) não pode ser menor que a inicial ({$startCarbon->format('d/m/Y')})."
                );
            }

            $newPeriods[] = [
                'start_date' => $startCarbon->toDateString(),
                'end_date'   => $endCarbon->toDateString(),
                'days'       => $startCarbon->diffInDays($endCarbon) + 1
            ];
        }

        $daysRequested = collect($newPeriods)->sum('days');

        $vacation = Vacation::firstOrCreate(
            ['user_id' => $user->id],
            ['periods' => []]
        );

        $existingPeriods = $vacation->periods ?? [];
        $daysUsed = collect($existingPeriods)->sum(fn ($p) =>
            Carbon::parse($p['start_date'])->diffInDays(Carbon::parse($p['end_date'])) + 1
        );

        $daysRemaining = 30 - $daysUsed;

        if ($daysRequested > $daysRemaining && !$request->has('confirm')) {
            return back()->withInput()->with('warning', 
                "Este colaborador já gozou {$daysUsed} dias de férias nos últimos 12 meses, restam apenas {$daysRemaining}. Deseja confirmar mesmo assim?"
            );
        }

        foreach ($newPeriods as $p) {
            $conflicts = Vacation::with('user')
                ->where('user_id', '!=', $user->id)
                ->get()
                ->filter(function ($v) use ($p) {
                    foreach ($v->periods ?? [] as $period) {
                        $start = Carbon::parse($period['start_date']);
                        $end   = Carbon::parse($period['end_date']);

                        if (
                            (Carbon::parse($p['start_date'])->between($start, $end)) ||
                            (Carbon::parse($p['end_date'])->between($start, $end)) ||
                            (Carbon::parse($p['start_date'])->lte($start) && Carbon::parse($p['end_date'])->gte($end))
                        ) {
                            return true;
                        }
                    }
                    return false;
                });
        
            if ($conflicts->isNotEmpty() && !$request->has('confirm')) {
                $names = $conflicts->map(function ($v) {
                    $periodsStr = collect($v->periods)->map(fn ($p) =>
                        Carbon::parse($p['start_date'])->format('d/m/Y') . ' até ' .
                        Carbon::parse($p['end_date'])->format('d/m/Y')
                    )->join(', ');
                    return $v->user->name . " ({$periodsStr})";
                })->join(', ');

                return back()->withInput()->with('warning',
                    "Já existe(m) colaborador(es) de férias em algum dos períodos informados: {$names}. Deseja confirmar mesmo assim?"
                );
            }
        }

        $vacation->periods = array_merge($existingPeriods, $newPeriods);
        $vacation->save();

        return redirect()->route('vacation.index')
            ->with('success', 
                "Período de férias do colaborador '{$user->name}' cadastrado(s) com sucesso."
            );
    }

    public function edit(Vacation $vacation)
    {
        $users = User::where('role', 'collaborator')->orderBy('name', 'asc')->get();

        return view ('vacation.edit', compact(['users', 'vacation']));
    }

    public function update(Request $request, Vacation $vacation)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'start_date'   => 'required|array|min:1',
            'start_date.*' => 'required|date',
            'end_date'     => 'required|array|min:1',
            'end_date.*'   => 'required|date'
        ], [
            'start_date.required' => 'Data de início é obrigatória.',
            'end_date.required'   => 'Data de término é obrigatória.'
        ]);

        $user = User::findOrFail($validated['user_id']);
        $newPeriods = [];

        foreach ($validated['start_date'] as $i => $start) {
            $end = $validated['end_date'][$i] ?? null;
            if (!$end) continue;

            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);

            if ($endCarbon->lt($startCarbon)) {
                return back()->withInput()->with('warning', 
                    "A data final ({$endCarbon->format('d/m/Y')}) não pode ser menor que a inicial ({$startCarbon->format('d/m/Y')})."
                );
            }

            $newPeriods[] = [
                'start_date' => $startCarbon->toDateString(),
                'end_date'   => $endCarbon->toDateString(),
                'days'       => $startCarbon->diffInDays($endCarbon) + 1
            ];
        }

        $daysRequested = collect($newPeriods)->sum('days');

        $otherVacations = Vacation::where('user_id', $user->id)
            ->where('id', '!=', $vacation->id)
            ->get();

        $daysUsed = $otherVacations->sum(function ($v) {
            return collect($v->periods ?? [])->sum(function ($p) {
                return Carbon::parse($p['start_date'])->diffInDays(Carbon::parse($p['end_date'])) + 1;
            });
        });

        $daysRemaining = 30 - $daysUsed;

        if ($daysRequested > $daysRemaining && !$request->has('confirm')) {
            return back()->withInput()->with('warning', 
                "Este colaborador já gozou {$daysUsed} dias de férias nos últimos 12 meses, restam apenas {$daysRemaining}. Deseja confirmar mesmo assim?"
            );
        }

        foreach ($newPeriods as $p) {
            $conflicts = Vacation::with('user')
                ->where('id', '!=', $vacation->id)
                ->where('user_id', '!=', $user->id)
                ->get()
                ->map(function ($v) use ($p) {
                    $conflictingPeriods = collect($v->periods ?? [])->filter(function ($period) use ($p) {
                        $start = Carbon::parse($period['start_date']);
                        $end   = Carbon::parse($period['end_date']);

                        return (
                            Carbon::parse($p['start_date'])->between($start, $end) ||
                            Carbon::parse($p['end_date'])->between($start, $end) ||
                            (Carbon::parse($p['start_date'])->lte($start) && Carbon::parse($p['end_date'])->gte($end))
                        );
                    });
                    
                    return $conflictingPeriods->isNotEmpty()
                        ? ['user' => $v->user, 'periods' => $conflictingPeriods->values()->all()]
                        : null;
                })
                ->filter();
        
            if ($conflicts->isNotEmpty() && !$request->has('confirm')) {
                $names = $conflicts->map(function ($c) {
                    $periodsStr = collect($c['periods'])->map(fn($p) =>
                        Carbon::parse($p['start_date'])->format('d/m/Y') . ' até ' .
                        Carbon::parse($p['end_date'])->format('d/m/Y')
                    )->join(', ');

                    return $c['user']->name . " ({$periodsStr})";
                })->join(', ');

                return back()->withInput()->with('warning',
                    "Já existe(m) colaborador(es) de férias em conflito com este período: {$names}. Deseja confirmar mesmo assim?"
                );
            }
        }

        $vacation->periods = $newPeriods;
        $vacation->user_id = $user->id;
        $vacation->save();

        return redirect()->route('vacation.index')
            ->with('success', 
                "Período(s) de férias do colaborador '{$user->name}' atualizado(s) com sucesso."
            );
    }

    public function destroy(Vacation $vacation)
    {
        $vacation->delete();

        return redirect()->back()->with('success', 'Período de férias do colaborador "' . $vacation->user->name . '" removido com sucesso.');
    }

    public function markAsRead(Vacation $vacation, $periodIndex)
    {
        $periods = $vacation->periods;

        if (isset($periods[$periodIndex])) {
            $periods[$periodIndex]['is_read'] = true;
            $vacation->periods = $periods;
            $vacation->save();
        }

        return redirect()->route('dashboard.index')
            ->with('success', 'Aviso de férias do colaborador "' . $vacation->user->name . '" marcado como lido.');
    }
}
