<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
       public function index(Request $request)
    {
        $query = User::query();
        
        // Filtro por nome
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filtro por painel
        if ($request->has('painel_filter') && !empty($request->painel_filter)) {
            $query->where('painel', $request->painel_filter);
        }
        
        $users = $query->paginate(10);
        $painelOptions = ['administrador', 'gerente', 'enfermeiro', 'médico', 'tecnico'];
        
        return view('users.index', compact('users', 'painelOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $painelOptions = ['administrador', 'gerente', 'enfermeiro', 'médico', 'tecnico'];
        return view('users.create', compact('painelOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'painel' => ['required', 'in:administrador,gerente,enfermeiro,médico,tecnico'],
            'receives_notifications' => ['boolean'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'painel' => $request->painel,
            'receives_notifications' => $request->receives_notifications ?? false,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $painelOptions = ['administrador', 'gerente', 'enfermeiro', 'médico', 'tecnico'];
        return view('users.edit', compact('user', 'painelOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'painel' => ['required', 'in:administrador,gerente,enfermeiro,médico,tecnico'],
            'receives_notifications' => ['boolean'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->painel = $request->painel;
        $user->receives_notifications = $request->receives_notifications ?? false;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
