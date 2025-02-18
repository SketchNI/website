<?php

use App\Models\Blog\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class, 'parent_id')->nullable();
            $table->string('name');
            $table->string('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
