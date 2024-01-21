<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $user = DB::table('users')->insertGetId([
        'over_name' => '仮田',
        'under_name' => '一郎',
        'over_name_kana' => 'カリタ',
        'under_name_kana' => 'イチロウ',
        'mail_address' => 'karita@test.com',
        'sex' => '2',
        'birth_day' => '1999-01-25',
        'role' => '2',
        'password' => Hash::make('test1234')
    ]);

    $subjects = DB::table('Subjects')->insertGetId([
        'subject' => '数学',
        'created_at' => '2024-01-01'
    ]);

    DB::table('subject_users')->insert([
        'user_id' => $user,
        'subject_id' => $subjects,
        'created_at' => now(),
    ]);
    }
}