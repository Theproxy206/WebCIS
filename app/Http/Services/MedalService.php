<?php

namespace App\Http\Services;

use App\Models\Medal;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\OrderDirection;

class MedalService {
    public function paginate(User $user, int $limit = 10, OrderDirection $order = OrderDirection::Asc): LengthAwarePaginator
    {
        return $user->medals()
        ->select([
            'med_serial',
            'med_name',
            'med_description',
            'med_path_image'
        ])
        ->orderBy('med_name', $order->value)
        ->paginate($limit);
    }

    public function count(User $user): int
    {
        return $user->medals()->count();
    }

    public function find(User $user, int $serial): ?Medal
    {
        return $user->medals()->findOrFail($serial);
    }

    public function has(User $user, int $serial): bool
    {
        return $user->medals()
        ->whereKey($serial)
        ->exists();
    }
}