<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_board(): void
    {
        $comment = Comment::factory()->create(['body' => 'こんにちは']);

        $this->get('/comments')
            ->assertOk()
            ->assertSee('こんにちは')
            ->assertSee($comment->user->name)
            ->assertDontSee('name="body"', false);
    }

    public function test_guest_cannot_post(): void
    {
        $this->post('/comments', ['body' => 'guest'])
            ->assertRedirect('/login');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_user_can_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/comments', ['body' => 'はじめまして'])
            ->assertRedirect('/comments');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'body' => 'はじめまして',
        ]);
    }

    public function test_user_id_cannot_be_spoofed(): void
    {
        $user = User::factory()->create();
        $victim = User::factory()->create();

        $this->actingAs($user)
            ->post('/comments', ['body' => 'なりすまし', 'user_id' => $victim->id]);

        $this->assertDatabaseHas('comments', ['body' => 'なりすまし', 'user_id' => $user->id]);
        $this->assertDatabaseMissing('comments', ['user_id' => $victim->id]);
    }

    public function test_body_is_validated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/comments', ['body' => ''])
            ->assertSessionHasErrors('body');
        $this->actingAs($user)->post('/comments', ['body' => str_repeat('a', 1001)])
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_body_is_html_escaped(): void
    {
        Comment::factory()->create(['body' => '<script>alert(1)</script>']);

        $this->get('/comments')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_user_name_is_html_escaped(): void
    {
        $user = User::factory()->create(['name' => '<img src=x onerror=alert(1)>']);
        Comment::factory()->for($user)->create();

        $this->get('/comments')
            ->assertDontSee('<img src=x onerror=alert(1)>', false);
    }

    public function test_owner_can_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)
            ->delete("/comments/{$comment->id}")
            ->assertRedirect('/comments');

        $this->assertModelMissing($comment);
    }

    public function test_other_user_cannot_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete("/comments/{$comment->id}")
            ->assertForbidden();

        $this->assertModelExists($comment);
    }

    public function test_guest_cannot_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->delete("/comments/{$comment->id}")
            ->assertRedirect('/login');

        $this->assertModelExists($comment);
    }

    public function test_delete_button_shown_only_to_owner(): void
    {
        $comment = Comment::factory()->create();
        $action = route('comments.destroy', $comment);

        $this->actingAs($comment->user)->get('/comments')->assertSee($action, false);
        $this->actingAs(User::factory()->create())->get('/comments')->assertDontSee($action, false);
    }

    public function test_posting_is_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)->post('/comments', ['body' => "post {$i}"]);
        }

        $this->actingAs($user)
            ->post('/comments', ['body' => 'too many'])
            ->assertStatus(429);

        $this->assertDatabaseCount('comments', 10);
    }

    public function test_post_without_csrf_token_is_rejected(): void
    {
        $user = User::factory()->create();

        // テスト時は CSRF チェックが無効化されるため、明示的に有効化して確認する
        $this->app->instance('env', 'local');

        $this->actingAs($user)
            ->post('/comments', ['body' => 'csrf'])
            ->assertStatus(419);

        $this->assertDatabaseCount('comments', 0);
    }
}
