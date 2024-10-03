<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$role = Role::all()->random();
		return [
			'displayName' => fake()->firstName() . ' ' . fake()->lastName(),
			'role_id' => $role['id'],
			'user_id' => User::factory()->create([
				'user_type_id' => 2
			])
		];
	}
}
