<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Service\ServiceListResource;
use App\Http\Resources\Service\ServiceShowResource;
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
    /**
     * get all service 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(){
        $services = Service::with('translations')->get(); 
        return ServiceListResource::collection($services); 
    }
    /**
     * get service by slug
     * @param mixed $slug
     * @return ServiceShowResource
     */
    public function show($slug)
    {
        $service = Service::with('translations')
            ->where('slug', $slug)
            ->firstOrFail();
    
        return new ServiceShowResource($service); 
    }
    
}
