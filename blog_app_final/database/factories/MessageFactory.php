<?php

namespace Database\Factories;

use App\Models\FriendShips;
use App\Models\GroupChat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $user_id=User::inRandomOrder()->first()->id;
        $chat_id=GroupChat::inRandomOrder()->first()->id;
        return [
            'user_id'=>$user_id,
            'chat_id'=>$chat_id,
            'content'=>fake()->paragraph(),
        ];
    }
}
