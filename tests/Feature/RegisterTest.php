<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_able_to_register_as_a_new_user()
    {
        // Arrange

        // Act
        $return = $this->post(route('register'), [
            'name' => 'Kauê de Magalhães',
            'email' => 'kauedemagalhaes27@gmail.com',
            'email_confirmation' => 'kauedemagalhaes27@gmail.com',
            'password' => 'senha Qualquer',
        ]);

        // Assert
        $return->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'Kauê de Magalhães',
            'email' => 'kauedemagalhaes27@gmail.com',
        ]);

        /** @var User $user */
        $user = User::whereEmail('kauedemagalhaes27@gmail.com')->firstOrFail();

        $this->assertTrue(
            Hash::check('senha Qualquer', $user->password),
            'Checking if password was saved and if is encrypted'
        );

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function name_should_be_required()
    {
        // Arrange

        // Act
        $return = $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'name' => __('validation.required', ['attribute' => 'name']),
            ]);
    }

    /** @test */
    public function name_should_have_a_max_of_255_characters()
    {
        // Arrange

        // Act
        $this->post(route('register'), [
            'name' => str_repeat('a', 256),
        ])
            ->assertSessionHasErrors([
                'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            ]);
    }

    /** @test */
    public function email_should_be_required()
    {
        // Arrange

        // Act
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_a_valid_email()
    {
        // Arrange

        // Act
        $this->post(route('register'), [
            'email' => 'invalid-email',
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_unique()
    {
        // Arrange
        User::factory()->create(['email' => 'test@email.com']);

        // Act
        $this->post(route('register'), ['email' => 'test@email.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_confirmed()
    {
        // Arrange

        // Act
        $this->post(route('register'), [
            'email' => 'test@email.com',
            'email_confirmation' => 'invalid-email',
        ])->assertSessionHasErrors([
            'email' => __('validation.confirmed', ['attribute' => 'email']),
        ]);
    }

    /** @test */
    public function password_should_be_required()
    {
        // Arrange

        // Act
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function password_should_have_at_least_1_uppercase()
    {
        // Arrange

        // Act
        $this->post(route('register'), [
            'password' => 'password',
        ])->assertSessionHasErrors([
                'password' => 'The password field must contain at least one uppercase and one lowercase letter.',
            ]);
    }
}
