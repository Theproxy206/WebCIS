<?php

namespace App\Http\Services;

use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Collection;

class MaterialService
{
    public static function getAll(): Collection
    {
        return Material::all();
    }

    public static function store(array $data): Material {
        $data['fk_materials_users'] = Auth::id(); // Asigna el usuario logueado
        return Material::query()->create($data);
    }

    public static function update(Material $material, array $data): Material {
        $material->update($data);
        return $material;
    }

    public static function delete(Material $material): bool {
        return $material->delete();
    }
}
