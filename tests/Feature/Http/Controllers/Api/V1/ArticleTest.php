<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Article;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_articles_resource(): void {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_unauthenticated_user_can_access_articles_resource(): void {

        $response = $this->get(route('articles.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_get_articles_with_pagination(): void {

        $user = User::factory()->create();
        Article::factory(20)->create();
        $perPage = 10;
        $page = 2;

        $response = $this->actingAs($user)->get(route('articles.index', [
            'perPage' => $perPage,
            'page' => $page
        ]));

        $response->assertSee('data');
        $this->assertCount($perPage, $response->json('data'));
        $this->assertEquals($page, $response->json('meta.current_page'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_authenticated_user_can_access_article_resource(): void {

        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.show', ['article' => $article->slug]));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_unauthenticated_user_cannot_access_article_show_resource(): void {

        $article = Article::factory()->create();

        $response = $this->get(route('articles.show', ['article' => $article->slug]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_article_show_resource_returns_correct_article(): void {

        $user = User::factory()->create();
        Article::factory(10)->create();

        $article = Article::first();

        $response = $this->actingAs($user)->get(route('articles.show', ['article' => $article->slug]));

        $this->assertEquals($response->json('data.slug'), $article->slug);
    }
}
