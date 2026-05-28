<?php

use App\Models\User;

it('rejects negative deposits', function () {
    $user = User::factory()->create([
        'money' => 50,
    ]);

    $response = $this
        ->actingAs($user)
        ->from(route('buyers.index'))
        ->post(route('deposit_money'), [
            'cantidad' => -10,
        ]);

    $response->assertSessionHasErrors('cantidad');
    expect((float) $user->fresh()->money)->toBe(50.0);
});

it('rejects negative withdrawals', function () {
    $user = User::factory()->create([
        'money' => 50,
    ]);

    $response = $this
        ->actingAs($user)
        ->from(route('buyers.index'))
        ->post(route('withdraw_money'), [
            'cantidad' => -10,
        ]);

    $response->assertSessionHasErrors('cantidad');
    expect((float) $user->fresh()->money)->toBe(50.0);
});
