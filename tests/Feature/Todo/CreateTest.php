<?php

namespace Tests\Feature\Todo;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_todo_item()
    {
        // Arrange
        /** @var User */
        $user = User::factory()->createOne();
        $assignedTo = User::factory()->createOne();

        $this->actingAs($user);

        // Act
        $request = $this->post(route('todo.store'), [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to' => $assignedTo->id,
        ]);

        // Assert
        $request->assertRedirect(route('todo.index'));
        
        $this->assertDatabaseHas('todos', [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to_id' => $assignedTo->id,
        ]);
    }

    /** @test */
    public function it_should_be_able_add_a_file_to_the_todo_item()
    {
        Storage::fake('s3');

        // Arrange
        //Create a user
        /** @var User */
        $user = User::factory()->createOne();

        //Login with the user
        $this->actingAs($user);

        //Act
        //Create a todo item
        $request = $this->post(route('todo.store'), [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to' => $user->id,
            'file' =>  UploadedFile::fake()->image('file.jpg'), //Fake file
        ]);

        //Assert
        //Check if file was uploaded
        Storage::disk('s3')->assertExists('todo/file.jpg');

        //Check if file path was saved in the database
        $this->assertDatabaseHas('todos', [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to_id' => $user->id,
            'file_path' => 'todo/file.jpg',
        ]);


    }
}
