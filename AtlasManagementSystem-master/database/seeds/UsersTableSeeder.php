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

    $subject = DB::table('subjects')->insertGetId([
        'subject' => '数学',
    ]);

    DB::table('subject_users')->insert([
        'user_id' => $user,
        'subject_id' => $subject,
        'created_at' => now(),
    ]);
    }
}