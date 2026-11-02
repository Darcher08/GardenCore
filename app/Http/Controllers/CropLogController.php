<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCropLogRequest;
use App\Models\Crop;
use Illuminate\Http\RedirectResponse;

class CropLogController extends Controller
{
    public function store(StoreCropLogRequest $request, Crop $crop): RedirectResponse
    {
        abort_unless($crop->user_id === $request->user()->id, 403);

        $crop->logs()->create($request->validated());

        return back()->with('success', 'Entrada registrada en la bitácora del cultivo.');
    }
}

