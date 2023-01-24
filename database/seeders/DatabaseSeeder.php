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
        // create one feed with 3 entries
        $feed = Feed::factory()->create();
        Entry::factory(3)->create([
            'feed_id' => $feed->id
        ]);
    }
}
