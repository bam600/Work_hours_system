<?php
/**Profileテーブルダミー作成 */
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

/**Userテーブルに紐づいたProfileを作成 */
class ProfileSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function ($user) {
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
