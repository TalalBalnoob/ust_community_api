<?php

namespace Database\Factories;

use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class StudentFactory extends Factory {
	protected static ?int $account_number = 202110500000;
	public function definition(): array {
		$major = Major::all()->random();
		return [
			'displayName' => fake()->firstName() . ' ' . fake()->lastName(),
			'major_id' => $major['id'],
			'level' => random_int(1, $major['years_of_study']),
			'user_id' => User::factory()->create([
				'user_type_id' => 1,
				'username' => static::$account_number++
			])
		];
	}
}
