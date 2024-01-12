<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_delete_a_todo()
    {
        // Arrange
        /** @var User $user */
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);
        
        // Act
        $this->delete(route('todo.destroy', $todo))
            ->assertRedirect(route('todo.index'));

        // Assert
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }
}
