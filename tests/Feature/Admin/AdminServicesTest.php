<?php

use App\Models\Service;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// --- Authorization ---
test('guests cannot access admin services', function () {
    $this->get(route('admin.services.index'))->assertRedirect(route('login'));
});

test('non-admin users are forbidden from admin services', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.services.index'))
        ->assertForbidden();
});

// --- Index ---
test('admin can view the services list', function () {
    Service::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.services.index'))
        ->assertOk();
});

// --- Store ---
test('admin can create a service', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [
            'name' => 'Airport Transfer',
            'category' => 'logistics',
            'description' => 'Comfortable airport pickup and drop-off.',
            'starting_price' => 75.00,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('services', ['name' => 'Airport Transfer', 'category' => 'logistics']);
});

test('admin cannot create a service without required fields', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [])
        ->assertSessionHasErrors(['name', 'category']);
});

// --- Update ---
test('admin can update a service', function () {
    $service = Service::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.services.update', $service), [
            'name' => 'Updated Service',
            'category' => 'logistics',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'Updated Service']);
});

// --- Destroy ---
test('admin can delete a service', function () {
    $service = Service::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.services.destroy', $service))
        ->assertRedirect();

    $this->assertModelMissing($service);
});
