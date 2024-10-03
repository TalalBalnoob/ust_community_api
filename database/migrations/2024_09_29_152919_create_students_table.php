<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('majors', function (Blueprint $table) {
			$table->id();
			$table->string('major')->unique();
			$table->integer('yeas_of_study');
			$table->string('department');
		});

		Schema::create('students', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
			$table->string('displayName');
			$table->foreignId('major_id')->references('id')->on('majors');
			$table->integer('level');
			$table->text('bio')->nullable();
			$table->string('imageUrl')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('students');
		Schema::dropIfExists('majors');
	}
};
