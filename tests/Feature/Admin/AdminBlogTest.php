<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

// --- Authorization ---
test('guests cannot access admin blog', function () {
    $this->get(route('admin.blog.index'))->assertRedirect(route('login'));
});

test('non-admin users are forbidden from admin blog', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.blog.index'))
        ->assertForbidden();
});

// --- Index ---
test('admin can view the blog posts list', function () {
    Post::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.blog.index'))
        ->assertOk();
});

// --- Create ---
test('admin can view the create post page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.blog.create'))
        ->assertOk();
});

// --- Store ---
test('admin can create a blog post', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.blog.store'), [
            'title' => 'Top 5 Safari Destinations in Kenya',
            'slug' => 'top-5-safari-destinations-kenya',
            'content' => 'Kenya is home to some of Africa\'s most iconic wildlife destinations...',
            'is_published' => false,
        ])
        ->assertRedirect(route('admin.blog.index'));

    $this->assertDatabaseHas('posts', [
        'slug' => 'top-5-safari-destinations-kenya',
        'user_id' => $this->admin->id,
    ]);
});

test('admin cannot create a post with a duplicate slug', function () {
    Post::factory()->create(['slug' => 'existing-post']);

    $this->actingAs($this->admin)
        ->post(route('admin.blog.store'), [
            'title' => 'Another Post',
            'slug' => 'existing-post',
            'content' => 'Some content here.',
        ])
        ->assertSessionHasErrors('slug');
});

test('admin cannot create a post without required fields', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.blog.store'), [])
        ->assertSessionHasErrors(['title', 'slug', 'content']);
});

// --- Edit ---
test('admin can view the edit post page', function () {
    $post = Post::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.blog.edit', $post))
        ->assertOk();
});

// --- Update ---
test('admin can update a blog post', function () {
    $post = Post::factory()->create([
        'user_id' => $this->admin->id,
        'title' => 'Original Title',
        'slug' => 'original-post-slug',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.blog.update', $post), [
            'title' => 'Updated Post Title',
            'slug' => 'original-post-slug',
            'content' => 'Updated content for the post.',
            'is_published' => true,
        ])
        ->assertRedirect(route('admin.blog.index'));

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Post Title', 'is_published' => true]);
});

// --- Destroy ---
test('admin can delete a blog post', function () {
    $post = Post::factory()->create([
        'user_id' => $this->admin->id,
        'slug' => 'post-to-delete',
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.blog.destroy', $post))
        ->assertRedirect(route('admin.blog.index'));

    $this->assertModelMissing($post);
});
