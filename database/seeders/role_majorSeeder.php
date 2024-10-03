<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class role_majorSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run(): void {
		Major::factory()->createMany([[
			'major' => 'Information Technology',
			'years_of_study' => 4,
			'department' => 'Engineering and Computers',
		], [
			'major' => 'cyber security',
			'years_of_study' => 4,
			'department' => 'Engineering and Computers',
		], [
			'major' => 'Architecture',
			'years_of_study' => 4,
			'department' => 'Engineering and Computers',
		], [
			'major' => 'Business management',
			'years_of_study' => 4,
			'department' => 'Management',
		], [
			'major' => 'Accounting',
			'years_of_study' => 4,
			'department' => 'Management',
		], [
			'major' => 'Pharmacy',
			'years_of_study' => 5,
			'department' => 'Health sciences',
		], [
			'major' => 'Therapeutic nutrition',
			'years_of_study' => 4,
			'department' => 'Health sciences',
		]]);

		Role::factory()->createMany([[
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
