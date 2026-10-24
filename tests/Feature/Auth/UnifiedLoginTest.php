<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

function loginRoleUser(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate($role, 'web'));

    return $user;
}

test('there is one shared login screen and old admin link redirects to it', function () {
    $this->get('/login')->assertOk()->assertSee('Đăng nhập hệ thống');
    $this->get('/adminlogin')->assertRedirect('/login');
    $this->post('/admin/login', [])->assertNotFound();
});

test('admin signing in through shared form goes to product manager', function () {
    $admin = loginRoleUser('admin');

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('admin.foods.index'));
});

test('customer signing in through the same form goes to public products', function () {
    $customer = loginRoleUser('customer');

    $response = $this->post('/login', [
        'email' => $customer->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($customer);
    $response->assertRedirect(route('foods.index'));
    $this->get(route('admin.foods.index'))->assertForbidden();
});

test('customer cannot be redirected into admin using an intended URL', function () {
    $customer = loginRoleUser('customer');

    $response = $this->withSession(['url.intended' => route('admin.foods.index')])
        ->post('/login', ['email' => $customer->email, 'password' => 'password']);

    $response->assertRedirect(route('foods.index'));
    $this->get(route('admin.foods.index'))->assertForbidden();
});

test('guests are sent to shared login before accessing admin tools', function () {
    $this->get(route('admin.foods.index'))->assertRedirect(route('login'));
});

test('inactive accounts cannot sign in', function () {
    $user = loginRoleUser('customer');
    $user->update(['is_active' => false]);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('role-aware dashboard forwards signed-in visitors to their own area', function () {
    $this->actingAs(loginRoleUser('admin'))->get('/dashboard')
        ->assertRedirect(route('admin.foods.index'));

    $this->actingAs(loginRoleUser('customer'))->get('/dashboard')
        ->assertRedirect(route('foods.index'));
});
