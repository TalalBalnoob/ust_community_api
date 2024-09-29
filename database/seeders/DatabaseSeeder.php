<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use App\Models\UserType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 */
	public function run(): void {
		UserType::factory()->create([
			'type' => 'student',
		]);

		UserType::factory()->create([
			'type' => 'staff',
		]);

		Student::factory(100)->create();
		Staff::factory(4)->create();
	}
}
