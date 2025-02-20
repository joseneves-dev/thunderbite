<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campaign;
use App\Models\Prize;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prize>
 */
class PrizeFactory extends Factory
{
    protected $model = Prize::class;

    public function definition()
    {
        return [
            'campaign_id' => Campaign::factory(), 
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence,
            'segment' => $this->faker->randomElement(['A', 'B', 'C']),
            'weight' => $this->faker->randomFloat(2, 1, 100),
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->addDays(5),
        ];
    }
}
