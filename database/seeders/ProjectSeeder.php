<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryTranslation;
use App\Models\ProjectTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProjectCategory::factory()
            ->count(5)
            ->has(ProjectCategoryTranslation::factory()->state(['locale' => 'en', 'name' => 'Real Estate EN']), 'translations')
            ->has(ProjectCategoryTranslation::factory()->state(['locale' => 'ar', 'name' => 'عقارات']), 'translations')
            ->create();

        Project::factory()
            ->count(15)
            ->hasAttached($categories->random(rand(1, 2)), [], 'categories')
            ->has(ProjectTranslation::factory()->state(['locale' => 'en']), 'translations')
            ->has(ProjectTranslation::factory()->state([
                'locale' => 'ar',
                'name' => 'اسم المشروع بالعربي',
                'body' => 'وصف المشروع بالكامل هنا',
                'problem' => 'المشكلة التي تم حلها',
                'solve' => 'كيف تم الحل',
                'tech' => ['لارافيل', 'بوتستراب']
            ]), 'translations')
            ->create();
    }
}
