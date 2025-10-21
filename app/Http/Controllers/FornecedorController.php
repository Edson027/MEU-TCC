<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class FornecedorController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query base
        $query = Fornecedor::query();
        
        // Filtro de busca
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('localizacao', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }
        
        // Ordenação
        $query->orderBy('created_at', 'desc');
        
        // Paginação
        $fornecedors = $query->paginate(10);
        
        return view('Fornecedor.index', compact('fornecedors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
        return view('Fornecedor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                'unique:fornecedors,nome'
            ],
            'descricao' => 'nullable|string|max:500',
            'localizacao' => 'required|string|max:255',
            'nif' => [
                'required',
                'string',
                'max:20',
                'unique:fornecedors,nif'
            ],
            'telefone' => [
                'required',
                'numeric',
                'unique:fornecedors,telefone'
            ]
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.unique' => 'Já existe um fornecedor com este nome.',
            'nif.required' => 'O campo NIF é obrigatório.',
            'nif.unique' => 'Já existe um fornecedor com este NIF.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.unique' => 'Já existe um fornecedor com este telefone.',
            'telefone.numeric' => 'O telefone deve conter apenas números.',
            'localizacao.required' => 'O campo localização é obrigatório.'
        ]);

        try {
            DB::beginTransaction();
            
            $fornecedor = Fornecedor::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('Fornecedor.show', $fornecedor->id)
                ->with('success', 'Fornecedor cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fornecedor $fornecedor)
    {
        return view('Fornecedor.show', compact('fornecedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fornecedor $fornecedor)
    {
        return view('Fornecedor.edit', compact('fornecedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        $validated = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fornecedors')->ignore($fornecedor->id)
            ],
            'descricao' => 'nullable|string|max:500',
            'localizacao' => 'required|string|max:255',
            'nif' => [
                'required',
                'string',
                'max:20',
                Rule::unique('fornecedors')->ignore($fornecedor->id)
            ],
            'telefone' => [
                'required',
                'numeric',
                Rule::unique('fornecedors')->ignore($fornecedor->id)
            ]
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.unique' => 'Já existe um fornecedor com este nome.',
            'nif.required' => 'O campo NIF é obrigatório.',
            'nif.unique' => 'Já existe um fornecedor com este NIF.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.unique' => 'Já existe um fornecedor com este telefone.',
            'telefone.numeric' => 'O telefone deve conter apenas números.',
            'localizacao.required' => 'O campo localização é obrigatório.'
        ]);

        try {
            DB::beginTransaction();
            
            $fornecedor->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('Fornecedor.show', $fornecedor->id)
                ->with('success', 'Fornecedor atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        try {
            DB::beginTransaction();
            
            $fornecedor->delete();
            
            DB::commit();
            
            return redirect()
                ->route('Fornecedor.index')
                ->with('success', 'Fornecedor excluído com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Erro ao excluir fornecedor: ' . $e->getMessage());
        }
    }

    /**
     * API - Listar fornecedores para select
     */
    public function apiFornecedores(Request $request)
    {
        $query = Fornecedor::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nome', 'like', "%{$request->search}%");
        }
        
        $fornecedores = $query->orderBy('nome')->get(['id', 'nome as text']);
        
        return response()->json($fornecedores);
    }

}
