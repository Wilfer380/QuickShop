<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '3001234567',
        'document' => 'CC123456',
        'role' => 'empleado',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
        'terms' => 'on',
    ]);

    $response
        ->assertRedirect(route('login', absolute: false))
        ->assertSessionHas('status', 'Cuenta creada. Ahora inicia sesión con tu correo y contraseña.');

    $this->assertGuest();
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'empleado',
    ]);
});
