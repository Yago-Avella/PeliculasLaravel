<?php

namespace Tests\Feature;

use App\Models\Genero;
use App\Models\Pelicula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardGenresTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_genre_filter_and_applies_it()
    {
        $user = User::factory()->create();

        $g1 = Genero::create(['name' => 'Acción']);
        $g2 = Genero::create(['name' => 'Comedia']);

        $p1 = Pelicula::create([
            'tmdb_id' => 1,
            'titulo' => 'Película 1',
            'anyo' => '2000',
            'duracion' => 90,
            'sinopsis' => 'Texto',
            'poster' => null,
            'media' => 5.00,
        ]);
        $p2 = Pelicula::create([
            'tmdb_id' => 2,
            'titulo' => 'Película 2',
            'anyo' => '2010',
            'duracion' => 95,
            'sinopsis' => 'Texto 2',
            'poster' => null,
            'media' => 6.35,
        ]);

        $p1->generos()->attach($g1);
        $p2->generos()->attach($g2);

        $user->peliculas()->attach([$p1->id, $p2->id]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Filtrar por género')
                 ->assertSee('Acción')
                 ->assertSee('Comedia')
                 ->assertSee('Filtrar por año')
                 ->assertSee('Filtrar por duración')
                 ->assertSee('Ordenar por')
                 // rating values should be formatted with two decimals
                 ->assertSee('5.00')
                 ->assertSee('6.35');

        // apply ordering by title (should show P1 then P2)
        $ordenarRespuesta = $this->actingAs($user)->get('/dashboard?sort=title');
        $ordenarRespuesta->assertSeeInOrder(['Película 1', 'Película 2']);

        // apply ordering by year (2000 then 2010)
        $ordenarRespuesta2 = $this->actingAs($user)->get('/dashboard?sort=year');
        $ordenarRespuesta2->assertSeeInOrder(['Película 1', 'Película 2']);

        // apply ordering by rating (5.00 then 6.35)
        $ordenarRespuesta3 = $this->actingAs($user)->get('/dashboard?sort=rating');
        $ordenarRespuesta3->assertSeeInOrder(['Película 1', 'Película 2'])
                         ->assertSee('5.00')
                         ->assertSee('6.35');

        // applying genre filter
        $response2 = $this->actingAs($user)->get('/dashboard?genre='.$g1->id);
        $response2->assertOk()
                  ->assertSee('Película 1')
                  ->assertDontSee('Película 2');

        // applying year filter
        $response3 = $this->actingAs($user)->get('/dashboard?year=2010');
        $response3->assertOk()
                  ->assertSee('Película 2')
                  ->assertDontSee('Película 1');

        // applying duration filter
        $response5 = $this->actingAs($user)->get('/dashboard?duration=95');
        $response5->assertOk()
                  ->assertSee('Película 2')
                  ->assertDontSee('Película 1');

        // applying combined filters (no match for genre=1 & year=2010 & duration=95)
        $response4 = $this->actingAs($user)->get('/dashboard?genre='.$g1->id.'&year=2010&duration=95');
        $response4->assertOk()
                  ->assertDontSee('Película 1')
                  ->assertDontSee('Película 2');
    }

    public function test_storing_movie_also_saves_new_genres()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/*' => Http::response([
                'id' => 123,
                'title' => 'Prueba',
                'release_date' => '2021-05-05',
                'runtime' => 120,
                'overview' => 'Descripcion',
                'poster_path' => '/poster.jpg',
                'vote_average' => 7,
                'genres' => [
                    ['id' => 10, 'name' => 'Drama'],
                    ['id' => 20, 'name' => 'Fantástico'],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('tmdb.store', ['tmdb_id' => 123]));

        $this->assertDatabaseHas('generos', ['name' => 'Drama']);
        $this->assertDatabaseHas('generos', ['name' => 'Fantástico']);

        $pelicula = Pelicula::where('tmdb_id',123)->first();
        $this->assertNotNull($pelicula);
        $this->assertTrue($pelicula->generos()->where('name', 'Drama')->exists());
        $this->assertTrue($pelicula->generos()->where('name', 'Fantástico')->exists());

        // and user should have the movie attached
        $this->assertTrue($user->peliculas()->where('tmdb_id',123)->exists());
    }
}
