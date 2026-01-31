<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Http\Services\MaterialService;
use App\Models\Material;
use Illuminate\Http\JsonResponse;

class MaterialController extends Controller
{
    public function index(): JsonResponse {
        return response()->json(MaterialService::getAll());
    }

    public function store(StoreMaterialRequest $request): JsonResponse {
        $material = MaterialService::store($request->validated());
        return response()->json(['message' => 'Created', 'data' => $material], 201);
    }

    public function show(Material $material): JsonResponse {
        return response()->json($material);
    }

    public function update(UpdateMaterialRequest $request, Material $material): JsonResponse {
        $updated = MaterialService::update($material, $request->validated());
        return response()->json(['message' => 'Updated', 'data' => $updated]);
    }

    public function destroy(Material $material): JsonResponse {
        MaterialService::delete($material);
        return response()->json(['message' => 'Deleted']);
    }
}
