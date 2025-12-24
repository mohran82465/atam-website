<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogCategoryTranslation;
use App\Models\BlogTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = BlogCategory::factory()
            ->count(5)
            ->has(BlogCategoryTranslation::factory()->state(['locale' => 'en', 'name' => 'Tech EN']), 'translations')
            ->has(BlogCategoryTranslation::factory()->state(['locale' => 'ar', 'name' => 'تكنولوجيا']), 'translations')
            ->create();

        Blog::factory()
            ->count(20)
            // Add 'categories' as the second parameter here
            ->hasAttached($categories->random(rand(1, 2)), [], 'categories')
            ->has(BlogTranslation::factory()->state(['locale' => 'en']), 'translations')
            ->has(BlogTranslation::factory()->state([
                'locale' => 'ar',
                'name' => 'عنوان المقال بالعربية',
                'body' => 'هذا هو نص المقال التجريبي باللغة العربية'
            ]), 'translations')
            ->create();

    }
}
