<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport; 
class UsuarioController extends Controller
{
     public function index(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');
    $role = $request->input('role');
    $sort = $request->input('sort', 'name');
    $direction = $request->input('direction', 'asc');

    $users = User::with('roles')
        ->search($search)
        ->active($status)
        ->withRole($role)
        ->orderBy($sort, $direction)
        ->paginate(10)
        ->withQueryString();

    $roles = Role::all();

    return view('Usuarios.index', compact('users', 'roles'));
}



    public function create()
    {
        $roles = Role::all();
        return view('Usuarios.creat', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'painel'=>$validated['painel'],
        ]);

        
        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('Usuarios.editar', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update($validated);
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Usuário removido com sucesso!');
    }

    public function changePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Senha alterada com sucesso!');
    }

    public function batchUpdate(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:users,id',
        'action' => 'required|in:activate,deactivate'
    ]);

    $status = $request->action === 'activate' ? true : false;

    User::whereIn('id', $request->ids)->update(['is_active' => $status]);

    return response()->json(['success' => true]);
}

public function export()
{
    return Excel::download(new UsersExport, 'usuarios_' . now()->format('d-m-Y') . '.xlsx');
}
}
