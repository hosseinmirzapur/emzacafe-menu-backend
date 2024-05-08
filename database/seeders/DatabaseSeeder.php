<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $branch = Branch::query()->create([
            'name' => 'شعبه مدیریتی',
            'active' => true,
            'note' => 'این شعبه جهت استفاده مشتری نیست و برای مدیریت بقیه شعبه هاست'
        ]);
        Admin::query()->create([
            'username' => 'superadmin',
            'password' => Hash::make('12345678'),
            'branch_id' => $branch->id
        ]);
    }
}
