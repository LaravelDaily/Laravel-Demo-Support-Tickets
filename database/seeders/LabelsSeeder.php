<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelsSeeder extends Seeder
{
    public function run()
    {
        $labels = [
            "bug", "question", "enhancement",
        ];

        foreach ($labels as $label) {
            Label::create([
                'name' => $label,
            ]);
        }
    }
}
