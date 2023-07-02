<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $possible_tags = ['Backend','Frontend','Full','Django','Laravel'
        ,'Node.js','DevOps','ML Engingering','MLOps','Data Analayst','Python'];
        $tags_index = array_rand($possible_tags,3);
        $tags = [];
        foreach($tags_index as $index):
            array_push($tags,$possible_tags[$index]);
        endforeach;
        
        $temp = str();
        foreach($tags as $index => $tag):
            if ($index==0):
                $temp = $tag;
            else:
                $temp = "$temp, $tag";
            endif;
        endforeach;
        
        return [
            //
            'title' => $this->faker->sentence(),
            'tags' => $temp,
            'company' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'location' => "{$this->faker->city()}, {$this->faker->country}",
            'description' => $this->faker->paragraph(8),

        ];
    }
}
