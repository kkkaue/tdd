<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_update_a_todo()
    {
        // Arrange
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);
        // Act
        $this->put(route('todo.update', $todo), [
            'title' => 'Updated Todo',
            'description' => 'Updated Todo Description',
            'assigned_to' => $user->id,
        ])->assertRedirect(route('todo.index'));

        $todo->refresh();

        // Assert

        $this->assertEquals('Updated Todo', $todo->title);
        $this->assertEquals('Updated Todo Description', $todo->description);
    }
}
