<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Todo $todo)
    {
        $todo->fill([
            'title' => request()->title,
            'description' => request()->description,
            'assigned_to_id' => request()->assigned_to,
        ]);

        $todo->save();

        return redirect()->route('todo.index');
    }
}
