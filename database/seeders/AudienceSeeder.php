<?php

namespace Database\Seeders;

use App\Models\Audience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AudienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $audiences = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'description' => 'Products primarily targeted toward men.',
                'is_active' => true,
            ],
            [
                'name' => 'Women',
                'slug' => 'women',
                'description' => 'Products primarily targeted toward women.',
                'is_active' => true,
            ],
            [
                'name' => 'Kids',
                'slug' => 'kids',
                'description' => 'Products primarily targeted toward kids.',
                'is_active' => true,
            ],
            [
                'name' => 'Unisex',
                'slug' => 'unisex',
                'description' => 'Products suitable for all genders.',
                'is_active' => true,
            ],
        ];

        foreach ($audiences as $audience) {
            Audience::updateOrCreate(
                ['slug' => $audience['slug']],
                $audience
            );
        }
    }
}
