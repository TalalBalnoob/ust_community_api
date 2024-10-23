<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Post;
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
		$this->call(role_majorSeeder::class);
		UserType::factory()->create([
			'type' => 'student',
		]);

		UserType::factory()->create([
			'type' => 'staff',
		]);

		Student::factory(200)->create();
		Staff::factory(20)->create();
		Follower::factory(400)->create();

		Post::factory(500)->create();
		Like::factory(1000)->create();
		Comment::factory(750)->create();
	}
}
