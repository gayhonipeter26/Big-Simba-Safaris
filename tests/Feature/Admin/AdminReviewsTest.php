<?php

use App\Models\Review;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// --- Authorization ---
test('guests cannot access admin reviews', function () {
    $this->get(route('admin.reviews.index'))->assertRedirect(route('login'));
});

test('non-admin users are forbidden from admin reviews', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.reviews.index'))
        ->assertForbidden();
});

// --- Index ---
test('admin can view the reviews list', function () {
    Review::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.reviews.index'))
        ->assertOk();
});

// --- Update (approve/reject) ---
test('admin can approve a review', function () {
    $review = Review::factory()->create(['is_approved' => false]);

    $this->actingAs($this->admin)
        ->put(route('admin.reviews.update', $review), ['is_approved' => true])
        ->assertRedirect();

    $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_approved' => true]);
});

test('admin can retract a review', function () {
    $review = Review::factory()->approved()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.reviews.update', $review), ['is_approved' => false])
        ->assertRedirect();

    $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_approved' => false]);
});

// --- Destroy ---
test('admin can delete a review', function () {
    $review = Review::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.reviews.destroy', $review))
        ->assertRedirect();

    $this->assertModelMissing($review);
});
