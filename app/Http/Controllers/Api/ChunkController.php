<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chunk\ChunkResource;
use App\Models\Chunk;
use Illuminate\Http\Request;

class ChunkController extends Controller
{

    public function index(Request $request)
    {
        // $locale = $request->query('lang', app()->getLocale());

        // $chunks = Chunk::with('translations')->get()->map(function ($chunk) use ($locale) {

        //     // group translations by locale
        //     $translations = $chunk->translations->mapWithKeys(function ($t) {
        //         return [
        //             $t->locale => [
        //                 'title' => $t->title,
        //                 'body' => $t->body,
        //             ]
        //         ];
        //     });

            
        //     return [
        //         'id' => $chunk->id,
        //         'page' => $chunk->page,
        //         'slug' => $chunk->slug,
        //         'thumbnail' => $chunk->thumbnail ? $chunk->thumbnail : null,
        //         'translations' => $translations,
 
        //     ];
        // });

        // return response()->json($chunks, 200);
        $chunks = Chunk::with('translations')->get(); 
        return ChunkResource::collection($chunks); 
    }

    public function showBySlug($slug, Request $request)
    {
        // $locale = $request->query('lang', app()->getLocale());

        // $chunk = Chunk::with('translations')->where('slug', $slug)->firstOrFail();

        // $translations = $chunk->translations->mapWithKeys(function ($t) {
        //     return [
        //         $t->locale => [
        //             'title' => $t->title,
        //             'body' => $t->body,
        //         ]
        //     ];
        // });


        // return response()->json([
        //     'id' => $chunk->id,
        //     'page' => $chunk->page,
        //     'slug' => $chunk->slug,
        //     'thumbnail' => $chunk->thumbnail ? $chunk->thumbnail  : null,
        //     'translations' => $translations,
      
        // ]); 

        $chunk = Chunk::with('translations')
        ->where('slug', $slug)
        ->firstOrFail();
        return new ChunkResource($chunk); 
        
        
    }


}
