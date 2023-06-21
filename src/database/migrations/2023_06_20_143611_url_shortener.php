<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('urlShortener', function (Blueprint $table) {
            $table->id()->index();
            $table->string('originalUrl')->index();
            $table->string('urlKey')->index();
            $table->string('active')->default('yes');
            $table->foreignId('userId')->index()->nullable();
            $table->string('activeOn')->nullable();
            $table->text('hasSvg')->nullable();
            $table->text('hasImage')->nullable();
            $table->string('shortUrl');
            $table->timestamp('createdAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urlShortener');
    }
};
