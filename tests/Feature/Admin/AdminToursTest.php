<?php

use App\Models\Destination;
use App\Models\Tour;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// --- Authorization ---
test('guests cannot access admin tours', function () {
    $this->get(route('admin.tours.index'))->assertRedirect(route('login'));
});

test('non-admin users are forbidden from admin tours', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.tours.index'))
        ->assertForbidden();
});

// --- Index ---
test('admin can view the tours list', function () {
    Tour::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.tours.index'))
        ->assertOk();
});

// --- Create ---
test('admin can view the create tour page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.tours.create'))
        ->assertOk();
});

// --- Store ---
test('admin can create a tour', function () {
    $destination = Destination::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.tours.store'), [
            'destination_id' => $destination->id,
            'name' => 'Serengeti Classic Safari',
            'slug' => 'serengeti-classic-safari',
            'price' => 1500.00,
            'duration' => '7 days',
            'itinerary' => [['day' => 1, 'description' => 'Arrive in Arusha']],
            'images' => ['https://example.com/safari.jpg'],
            'whats_included' => ['Accommodation', 'Meals'],
            'whats_excluded' => ['Flights'],
        ])
        ->assertRedirect(route('admin.tours.index'));

    $this->assertDatabaseHas('tours', ['slug' => 'serengeti-classic-safari']);
});

test('admin cannot create a tour with a duplicate slug', function () {
    $tour = Tour::factory()->create(['slug' => 'existing-slug']);

    $this->actingAs($this->admin)
        ->post(route('admin.tours.store'), [
            'destination_id' => $tour->destination_id,
            'name' => 'Another Tour',
            'slug' => 'existing-slug',
            'price' => 500,
            'duration' => '3 days',
            'itinerary' => [['day' => 1, 'description' => 'Start']],
            'images' => ['https://example.com/img.jpg'],
            'whats_included' => ['Meals'],
            'whats_excluded' => ['Flights'],
        ])
        ->assertSessionHasErrors('slug');
});

// --- Edit ---
test('admin can view the edit tour page', function () {
    $tour = Tour::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.tours.edit', $tour))
        ->assertOk();
});

// --- Update ---
test('admin can update a tour', function () {
    $tour = Tour::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.tours.update', $tour), [
            'destination_id' => $tour->destination_id,
            'name' => 'Updated Tour Name',
            'slug' => $tour->slug,
            'price' => 2000.00,
            'duration' => '5 days',
            'itinerary' => [['day' => 1, 'description' => 'Updated day 1']],
            'images' => ['https://example.com/new.jpg'],
            'whats_included' => ['Accommodation'],
            'whats_excluded' => ['Flights'],
        ])
        ->assertRedirect(route('admin.tours.index'));

    $this->assertDatabaseHas('tours', ['id' => $tour->id, 'name' => 'Updated Tour Name']);
});

// --- Destroy ---
test('admin can delete a tour', function () {
    $tour = Tour::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.tours.destroy', $tour))
        ->assertRedirect(route('admin.tours.index'));

    $this->assertModelMissing($tour);
});
