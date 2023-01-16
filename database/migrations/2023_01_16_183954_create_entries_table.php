<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            //for this foreign key, decide if want to do cascade on update or delete
            $table->foreignId('feed_id')->constrained('feeds');
            $table->string('entry_url')->unique();
            $table->string('entry_title');
            $table->text('entry_teaser');
            $table->text('entry_content');
            $table->timestamp('entry_last_updated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
};
