<?php

use App\Models\User;

it('redirects unauthenticated guest to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

it('allows authenticated users to view catalog', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});
