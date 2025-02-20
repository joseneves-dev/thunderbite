<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Prize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prize::query()->delete();

        $campaigns = Campaign::all();

        foreach ($campaigns as $campaign) {
            Prize::insert([
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 1',
                    'segment' => 'low',
                    'image' => '1.png',
                    'weight' => '25.00',
                    'daily_volume' => '1000',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 2',
                    'segment' => 'low',
                    'image' => '2.png',
                    'weight' => '25.00',
                    'daily_volume' => '1000',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 3',
                    'image' => '3.png',
                    'segment' => 'low',
                    'weight' => '50.00',
                    'daily_volume' => '1000',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 1',
                    'image' => '4.png',
                    'segment' => 'med',
                    'weight' => '25.00',
                    'daily_volume' => '500',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 2',
                    'image' => '5.png',
                    'segment' => 'med',
                    'weight' => '25.00',
                    'daily_volume' => '500',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 3',
                    'image' => '6.png',
                    'segment' => 'med',
                    'weight' => '50.00',
                    'daily_volume' => '500',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 1',
                    'image' => '1.png',
                    'segment' => 'high',
                    'weight' => '25.00',
                    'daily_volume' => '100',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 2',
                    'image' => '4.png',
                    'segment' => 'high',
                    'weight' => '25.00',
                    'daily_volume' => '200',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 3',
                    'image' => '7.png',
                    'segment' => 'high',
                    'weight' => '50.00',
                    'daily_volume' => '300',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
            ]);
        }
    }
}
