<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campaign;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->addDays(10),
        ];
    }
}
