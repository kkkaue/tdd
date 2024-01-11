<?php

namespace Tests\Feature\Todo;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_todo_item(){
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
}
