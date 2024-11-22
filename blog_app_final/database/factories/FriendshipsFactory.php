<?php

namespace Database\Factories;

use App\Models\FriendShips;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=FriendShips>
 */
class FriendshipsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do{
            $user_id1 = User::inRandomOrder()->first()->id;
            $user_id2 = User::inRandomOrder()->first()->id;
        }
        while ($user_id1 == $user_id2 || FriendShips::where([['user_id1', $user_id1], ['user_id2', $user_id2]])->exists());   
        return [
            'user_id1' => $user_id1,
            'user_id2' => $user_id2
        ];
    }
}
