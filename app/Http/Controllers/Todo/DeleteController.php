<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todo.index');
    }
}
