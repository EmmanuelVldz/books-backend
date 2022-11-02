<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class BooksApiTest extends TestCase
{   
    use RefreshDataBase;
    
    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(3)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])
            ->assertJsonFragment([
                'title' => $books[1]->title
            ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /** @test */
    public function can_create_books()
    {
        $this->postJson(route('books.store'), [])
            ->dump();

        $this->postJson(route('books.store'), [
            'title' => 'Test book'
        ])->assertJsonFragment([
            'title' => 'Test book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Test book'
        ]);
    }

    /** @test */
    public function can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Edited book'
        ])->assertJsonFragment([
            'title' => 'Edited book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Edited book'
        ]);
    }

    /** @test */
    public function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();


        $this->assertDatabaseCount('books', 0);
    }
}
