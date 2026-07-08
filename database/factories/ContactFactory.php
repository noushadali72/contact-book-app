<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name"=>fake()->name(),
            "email"=>fake()->email(),
            "phone"=>fake()->phoneNumber(),
            "address"=>fake()->address(),
            "notes"=>fake()->paragraph(4),
            "group_id"=> Group::factory()
        ];  
    }
}
