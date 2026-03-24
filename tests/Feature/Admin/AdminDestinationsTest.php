<?php

use App\Models\Destination;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// --- Authorization ---
test('guests cannot access admin destinations', function () {
    $this->get(route('admin.destinations.index'))->assertRedirect(route('login'));
});

test('non-admin users are forbidden from admin destinations', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.destinations.index'))
        ->assertForbidden();
});

// --- Index ---
test('admin can view the destinations list', function () {
    Destination::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.destinations.index'))
        ->assertOk();
});

// --- Create ---
test('admin can view the create destination page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.destinations.create'))
        ->assertOk();
});

// --- Store ---
test('admin can create a destination', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.destinations.store'), [
            'name' => 'Maasai Mara',
            'slug' => 'maasai-mara',
            'overview' => 'World-renowned savannah in southwest Kenya.',
            'image' => 'https://example.com/maasai-mara.jpg',
        ])
        ->assertRedirect(route('admin.destinations.index'));

    $this->assertDatabaseHas('destinations', ['slug' => 'maasai-mara']);
});

test('admin cannot create a destination with a duplicate slug', function () {
    Destination::factory()->create(['slug' => 'existing-destination']);

    $this->actingAs($this->admin)
        ->post(route('admin.destinations.store'), [
            'name' => 'Another Destination',
            'slug' => 'existing-destination',
            'overview' => 'Some overview.',
            'image' => 'https://example.com/img.jpg',
        ])
        ->assertSessionHasErrors('slug');
});

test('admin cannot create a destination without required fields', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.destinations.store'), [])
        ->assertSessionHasErrors(['name', 'slug', 'overview', 'image']);
});

// --- Edit ---
test('admin can view the edit destination page', function () {
    $destination = Destination::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.destinations.edit', $destination))
        ->assertOk();
});

// --- Update ---
test('admin can update a destination', function () {
    $destination = Destination::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.destinations.update', $destination), [
            'name' => 'Updated Destination Name',
            'slug' => $destination->slug,
            'overview' => 'Updated overview text.',
            'image' => 'https://example.com/updated.jpg',
        ])
        ->assertRedirect(route('admin.destinations.index'));

    $this->assertDatabaseHas('destinations', ['id' => $destination->id, 'name' => 'Updated Destination Name']);
});

// --- Destroy ---
test('admin can delete a destination', function () {
    $destination = Destination::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.destinations.destroy', $destination))
        ->assertRedirect(route('admin.destinations.index'));

    $this->assertModelMissing($destination);
});
