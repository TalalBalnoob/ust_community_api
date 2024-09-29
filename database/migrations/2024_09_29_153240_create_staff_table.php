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
		Schema::create('staff', function (Blueprint $table) {
			$table->id()->primary();
			$table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
			$table->string('displayName');
			$table->string('department');
			$table->string('role');
			$table->text('bio')->nullable();
			$table->string('imageUrl')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('staff');
	}
};
