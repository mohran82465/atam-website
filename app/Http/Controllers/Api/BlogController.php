<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['translations', 'categories.translations'])->get();
        return response()->json($blogs->map(fn($b) => $this->formatBlog($b)), 200);
    }

    public function show($slug)
    {
        $blog = Blog::with(['translations', 'categories.translations'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($this->formatBlog($blog), 200);
    }

    private function formatBlog($blog)
    {
        return [
            'id' => $blog->id,
            'slug' => $blog->slug,
            'image' => $blog->image ?: null,
            'translations' => $this->formatTranslations($blog->translations),
            'categories' => $this->formatCategories($blog->categories),
        ];
    }

    private function formatCategories($categories)
    {
        return $categories->map(fn($cat) => $this->formatSingleCategory($cat));
    }

    private function formatSingleCategory($cat)
    {
        return [
            'id' => $cat->id,
            'slug' => $cat->slug,
            'image' => $cat->image ?: null,
            'translations' => $this->formatTranslationsCategory($cat->translations),
        ];
    }

    private function formatTranslations($translations)
    {
        return $translations->mapWithKeys(fn($t) => [
            $t->locale => [
                'name' => $t->name,
                'body' => $t->body,
            ]
        ]);
    }

    private function formatTranslationsCategory($translations)
    {
        return $translations->mapWithKeys(fn($t) => [
            $t->locale => [
                'name' => $t->name,
            ]
        ]);
    }


}
