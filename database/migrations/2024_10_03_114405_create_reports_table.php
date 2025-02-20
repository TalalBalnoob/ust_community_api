<?php

use App\Models\Reports_Action;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action');
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->references('id')->on('posts');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('reviewer_id')->nullable()->references('id')->on('users');
            $table->text('report_text');
            $table->boolean('isReviewed')->default(false);
            $table->boolean('isValid')->nullable();
            $table->foreignId('action')->nullable()->references('id')->on('reports_actions');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
