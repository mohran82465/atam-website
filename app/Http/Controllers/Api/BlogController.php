<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * get all the blogs 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search     = $request->query('search');
        $categoryId = $request->query('category_id');
    
        $blogs = Blog::query()
            ->where('status', true)
            ->with([
                'translations', 
                'categories.translations'
            ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('translations', function ($q) use ($search) {
                    $q->where(function($inner) use ($search) {
                        $inner->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('body', 'LIKE', "%{$search}%");
                    });
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('blog_categories.id', $categoryId);
                });
            })
            ->latest() 
            ->paginate(10);
    
        return BlogResource::collection($blogs); 
    }

    /**
     * get the blog by slug
     * @param mixed $slug
     * @return BlogResource
     */
    public function show($slug)
    {
        $blog = Blog::with(['translations', 'categories.translations'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new BlogResource($blog   ); 
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
