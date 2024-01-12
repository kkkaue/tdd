<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        $this->validate(request(), [
            'file' => ['nullable', 'mimes:jpg,png,jpeg,gif'],
        ]);

        Todo::query()
            ->create([
                'title' => request()->title,
                'description' => request()->description,
                'assigned_to_id' => request()->assigned_to,
                'file_path' => $this->getUploadedFilePath(),
            ]);

        return redirect()->route('todo.index');
    }

    public function getUploadedFilePath(){
        if(!request()->hasFile('file')){
            return null;
        }
        return request()
            ->file('file')
            ->storeAs(
                path: 'todo',
                name: request()->file('file')->getClientOriginalName(),
                options: ['disk' => 's3']
            );
    }
}
