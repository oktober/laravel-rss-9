<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feed;
use App\Models\Entry;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        // $feed = factory(App\Feed::class)->create();
        $feed = Feed::factory()->create();

        // factory(App\Entry::class, 3)->create([
        //     'feed_id' => $feed->id
        // ]);
        Entry::factory(3)->create([
            'feed_id' => $feed->id
        ]);
    }
}
