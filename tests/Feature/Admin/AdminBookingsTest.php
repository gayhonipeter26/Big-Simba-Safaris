<?php

use App\Models\Booking;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// --- Authorization ---
test('guests cannot access admin bookings', function () {
    $this->get(route('admin.bookings.index'))->assertRedirect(route('login'));
});

test('non-admin users are forbidden from admin bookings', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.bookings.index'))
        ->assertForbidden();
});

// --- Index ---
test('admin can view the bookings list', function () {
    Booking::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.bookings.index'))
        ->assertOk();
});

// --- Update status ---
test('admin can update a booking status', function () {
    $booking = Booking::factory()->create(['status' => 'pending']);

    $this->actingAs($this->admin)
        ->put(route('admin.bookings.update', $booking), ['status' => 'confirmed'])
        ->assertRedirect();

    $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
});

test('admin cannot set an invalid booking status', function () {
    $booking = Booking::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.bookings.update', $booking), ['status' => 'invalid-status'])
        ->assertSessionHasErrors('status');
});

// --- Destroy ---
test('admin can delete a booking', function () {
    $booking = Booking::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.bookings.destroy', $booking))
        ->assertRedirect();

    $this->assertModelMissing($booking);
});
