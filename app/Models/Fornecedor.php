<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Fornecedor extends Model
{
     use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fornecedors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'localizacao',
        'nif',
        'telefone'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'telefone' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Scope para buscar fornecedores por termo
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nome', 'like', "%{$term}%")
              ->orWhere('nif', 'like', "%{$term}%")
              ->orWhere('localizacao', 'like', "%{$term}%")
              ->orWhere('telefone', 'like', "%{$term}%");
        });
    }

    /**
     * Scope para fornecedores com estoque baixo (se relacionar com medicamentos)
     */
    public function scopeComProblemasEstoque($query)
    {
        return $query->whereHas('medicamentos', function ($q) {
            $q->where('stock', '<=', DB::raw('minimum_stock'));
        });
    }

    /**
     * Relacionamento com medicamentos (se houver)
     */
    public function medicamentos(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }

      public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    /**
     * Accessor para nome em maiúsculas
     */
    public function getNomeUpperCaseAttribute()
    {
        return strtoupper($this->nome);
    }

    /**
     * Accessor para localização formatada
     */
    public function getLocalizacaoFormatadaAttribute()
    {
        return ucwords(strtolower($this->localizacao));
    }

    /**
     * Mutator para garantir que o NIF seja armazenado em maiúsculas
     */
    public function setNifAttribute($value)
    {
        $this->attributes['nif'] = strtoupper($value);
    }

    /**
     * Mutator para garantir que o nome seja armazenado com a primeira letra maiúscula
     */
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = ucwords(strtolower($value));
    }
}
