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
        $todo = Todo::factory()->createOne([
            'assigned_to_id' => $user->id,
        ]);

        $this->actingAs($user);
        
        // Act
        $this->delete(route('todo.destroy', $todo))
            ->assertRedirect(route('todo.index'));

        // Assert
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    /** @test */
    public function only_the_assigned_user_should_be_able_to_delete_a_todo()
    {
        // Arrange
        /** @var User $user */
        $user1 = User::factory()->createOne();
        $todoUser1 = Todo::factory()->createOne([
            'assigned_to_id' => $user1->id,
        ]);

        /** @var User $user2 */
        $user2 = User::factory()->createOne();
        
        // Act
        $this->actingAs($user2);
        $this->delete(route('todo.destroy', $todoUser1))
            ->assertForbidden();

        // Assert
        $this->assertDatabaseHas('todos', [
            'id' => $todoUser1->id,
        ]);

        // Act
        $this->actingAs($user1);
        $this->delete(route('todo.destroy', $todoUser1))
            ->assertRedirect(route('todo.index'));

        // Assert
        $this->assertDatabaseMissing('todos', [
            'id' => $todoUser1->id,
        ]);
    }
}
