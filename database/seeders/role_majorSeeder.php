<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class role_majorSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run(): void {
		Major::factory()->create([[
			'major' => 'Information Technology',
			'year_of_study' => 4,
			'department' => 'Engineering and Computers',
		], [
			'major' => 'cyber security',
			'year_of_study' => 4,
			'department' => 'Engineering and Computers',
		], [
			'major' => 'Architecture',
			'year_of_study' => 4,
			'department' => 'Engineering and Computers',
		], [
			'major' => 'Business management',
			'year_of_study' => 4,
			'department' => 'Management',
		], [
			'major' => 'Accounting',
			'year_of_study' => 4,
			'department' => 'Management',
		], [
			'major' => 'Pharmacy',
			'year_of_study' => 5,
			'department' => 'Health sciences',
		], [
			'major' => 'Therapeutic nutrition',
			'year_of_study' => 4,
			'department' => 'Health sciences',
		]]);

		Staff::factory()->createMany([[
			'role' => 'lecturer'
		], [
			'role' => 'Head of department'
		], [
			'role' => 'Accountant'
		], [
			'role' => 'Student Affairs'
		], [
			'role' => 'Public Relations'
		], [
			'role' => 'Secretary'
		], [
			'role' => 'Branch Manager'
		], [
			'role' => 'Deputy Branch Manager'
		], [
			'role' => 'Admission and Registration'
		]]);
	}
}
