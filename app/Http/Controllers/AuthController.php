<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function index() {
        return view ('auth.index');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => 'email|required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Credenciais inválidas.']);
        }

        auth()->login($user);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Deslogado com sucesso.');
    }

    public function user(Request $request) {
        $authUser = auth()->user();
        
        $query = User::query();

        if ($authUser->role !== 'admin') {
            $query->where('id', $authUser->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }   

        $users = $query->orderBy('name', 'asc')->get();

        return view ('user.index', compact('authUser', 'users'));
    }

    public function create() {
        return view ('user.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'                      => 'string|required',
            'email'                     => 'email|required|unique:users,email',
            'role'                      => 'string|required',
            'admission_date'            => 'nullable|date',
            'birth_date'                => 'nullable|date',
            'position'                  => 'nullable|string',
            'password'                  => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*?&]/'
            ],
            'cep'                       => 'required|string|max:9',
            'address'                   => 'required|string',
            'number'                    => 'required|string',
            'phone'                     => 'required|string',
            'convenio'                  => 'required|in:sim,nao',
            'convenio_qual'             => 'nullable|required_if:convenio,sim|string',
            'is_management'             => 'boolean'
        ], [
            'name.required'             => 'O nome é obrigatório.',
            'email.required'            => 'O e-mail é obrigatório.',
            'email.unique'              => 'E-mail já cadastrado.',
            'role.required'             => 'O cargo é obrigatório.',
            'password.required'         => 'A senha é obrigatória.',
            'password.regex'            => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
            'password.min'              => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed'        => 'As senhas não coincidem.',
            'cep.required'              => 'O CEP é obrigatório.',
            'address.required'          => 'O endereço é obrigatório.',
            'number.required'           => 'O número é obrigatório.',
            'phone.required'            => 'O telefone é obrigatório.',
            'convenio.required'         => 'O campo convênio é obrigatório.',
            'convenio_qual.required_if' => 'O campo "Qual?" é obrigatório quando convênio está marcado como sim.'
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => bcrypt($request->password),
            'role'           => $request->role,
            'admission_date' => $request->admission_date,
            'birth_date'     => $request->birth_date,
            'position'       => $request->position,
            'cep'            => $request->cep,
            'address'        => $request->address,
            'number'         => $request->number,
            'complement'     => $request->complement,
            'phone'          => $request->phone,
            'emergency_phone'=> $request->emergency_phone,
            'convenio'       => $request->convenio === 'sim',
            'convenio_qual'  => $request->convenio_qual,
            'is_management'  => $request->is_management
        ]);

        return redirect()->route('usuario.user')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user) {
        $authUser = auth()->user();

        return view ('user.edit', compact('user', 'authUser'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name'     => 'string|required',
            'email'    => [
                'email',
                'required',
                Rule::unique('users')->ignore($user->id)
            ],
            'role'     => 'string|required',
            'admission_date' => 'nullable|date',
            'birth_date'     => 'nullable|date',
            'position'       => 'nullable|string',
            'password' => [
                'nullable',
                'string',
                'min:8',    
                'confirmed',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*?&]/'
            ],
            'cep'                       => 'required|string|max:9',
            'address'                   => 'required|string',
            'number'                    => 'required|string',
            'phone'                     => 'required|string',
            'emergency_phone'           => 'nullable|string',
            'convenio'                  => 'required|in:sim,nao',
            'convenio_qual'             => 'nullable|required_if:convenio,sim|string',
            'is_management'             => 'boolean'
        ], [
            'name.required'             => 'O nome é obrigatório.',
            'email.required'            => 'O e-mail é obrigatório.',
            'email.email'               => 'O e-mail deve ser válido.',
            'email.unique'              => 'E-mail já cadastrado.',
            'role.required'             => 'O campo função é obrigatório.',
            'admission_date.date'       => 'A data de admissão deve ser válida.',
            'birth_date.date'           => 'A data de nascimento deve ser válida.',
            'password.min'              => 'A senha deve ter pelo menos 8 caracteres.',
            'password.regex'            => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
            'password.confirmed'        => 'As senhas não coincidem.',
            'cep.required'              => 'O CEP é obrigatório.',
            'address.required'          => 'O endereço é obrigatório.',
            'number.required'           => 'O número é obrigatório.',
            'phone.required'            => 'O telefone é obrigatório.',
            'convenio.required'         => 'O campo convênio é obrigatório.',
            'convenio_qual.required_if' => 'O campo "Qual?" é obrigatório quando convênio está marcado como sim.'
        ]);

        $user->update([
            'name'             => $request->name,
            'email'            => $request->email,
            'role'             => $request->role,
            'admission_date'   => $request->admission_date,
            'birth_date'       => $request->birth_date,
            'position'         => $request->position,
            'cep'              => $request->cep,
            'address'          => $request->address,
            'number'           => $request->number,
            'complement'       => $request->complement,
            'phone'            => $request->phone,
            'emergency_phone'  => $request->emergency_phone,
            'convenio'         => $request->convenio === 'sim',
            'convenio_qual'    => $request->convenio === 'sim' ? $request->convenio_qual : null,
            'is_management'    => $request->is_management
        ]);

        if (filled($request->password)) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return redirect()->route('usuario.user')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user) {
        $user->delete();

        return redirect()->route('usuario.user')->with('success', 'Usuário excluído com sucesso.');
    }

    public function respostas(User $user) {
        $answers = $user->answers()
            ->with(['diagnostic.options' => function ($query) {
                $query->select('id', 'diagnostic_id', 'text', 'points'); 
            }, 'diagnostic', 'option'])
            ->get();

        $grouped = $answers->groupBy('diagnostic_id');

        $diagnostics = $grouped->map(function ($answers) {
            $diagnostic = $answers->first()->diagnostic;

            $selectedOptions = $answers->pluck('option_id')->toArray();

            return [
                'diagnostic_id' => $diagnostic->id,
                'question'      => $diagnostic->question,
                'options'       => $diagnostic->options->map(function ($option) use ($selectedOptions) {
                    return [
                        'id'       => $option->id,
                        'text'     => $option->text,
                        'selected' => in_array($option->id, $selectedOptions),
                        'points'   => (int) $option->points
                    ];
                })->values()
            ];
        })->values();

        return response()->json($diagnostics);
    }
}