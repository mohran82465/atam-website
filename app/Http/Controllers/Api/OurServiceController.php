<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class OurServiceController extends Controller
{
    private function formatTranslationsForAllServices($translations){
        return $translations->mapWithKeys(function($t){
          return[

              $t->locale => [
                'title' =>$t->title, 
                'description' =>$t->short_description, 
                'features' =>$t->features, 
              ]
          ];
           });
    }
    private function formatTranslationsForService($translations){
        return $translations->mapWithKeys(function($t){
          return[

              $t->locale => [
                'title' =>$t->title, 
                'description' =>$t->long_description, 
                'features' =>$t->features, 
              ]
          ];
           });
    }
    
    public function index(){
        $services = Service::with('translations')->get(); 
        $data = $services->map(function($service){
            return [
                'id'=> $service->id,
                'name'=> $service->name,
                'slug'=> $service->slug,
                'icon'=> $service->icon,
                'translations'=> $this->formatTranslationsForAllServices($service->translations),
            ];
        });

        return response()->json($data, 200);
    }
    public function show($slug)
    {
        $service = Service::with('translations')
            ->where('slug', $slug)
            ->firstOrFail();
    
        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'slug' => $service->slug,
            'icon' => $service->icon,
            'translations' => $this->formatTranslationsForService($service->translations),
        ], 200);
    }
    
}
