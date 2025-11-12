<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Publishers
        $publishers = [
            ['name' => 'Gramedia', 'address' => 'Jl. Sudirman No.1, Jakarta'],
            ['name' => 'Erlangga', 'address' => 'Jl. Mangga Dua No.10, Jakarta'],
            ['name' => 'Mizan', 'address' => 'Jl. Asia Afrika No.88, Bandung'],
            ['name' => 'Pustaka Pelajar', 'address' => 'Jl. Diponegoro No.15, Yogyakarta'],
            ['name' => 'Bukune', 'address' => 'Jl. Raya Bogor No.25, Depok'],
        ];

        foreach ($publishers as $pub) {
            Publisher::create($pub);
        }

        // Authors
        $authors = [
            ['name' => 'J.K. Rowling', 'bio' => 'Author of Harry Potter series.'],
            ['name' => 'George R.R. Martin', 'bio' => 'Author of A Song of Ice and Fire.'],
            ['name' => 'Agatha Christie', 'bio' => 'Famous mystery writer.'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }


        // Books
        $authorsAll = Author::all();
        $publishersAll = Publisher::all();

        $books = [
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'description' => 'First book in the Harry Potter series.',
                'author_id' => $authorsAll->where('name', 'J.K. Rowling')->first()->id ?? $authorsAll->random()->id,
                'publisher_id' => $publishersAll->where('name', 'Gramedia')->first()->id ?? $publishersAll->random()->id,
            ],
            [
                'title' => 'A Game of Thrones',
                'description' => 'First book in A Song of Ice and Fire series.',
                'author_id' => $authorsAll->where('name', 'George R.R. Martin')->first()->id ?? $authorsAll->random()->id,
                'publisher_id' => $publishersAll->where('name', 'Erlangga')->first()->id ?? $publishersAll->random()->id,
            ],
            [
                'title' => 'Murder on the Orient Express',
                'description' => 'Famous mystery novel by Agatha Christie.',
                'author_id' => $authorsAll->where('name', 'Agatha Christie')->first()->id ?? $authorsAll->random()->id,
                'publisher_id' => $publishersAll->where('name', 'Mizan')->first()->id ?? $publishersAll->random()->id,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456')
        ]);
    }
}
