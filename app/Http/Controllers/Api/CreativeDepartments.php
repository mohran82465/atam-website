<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CreativeDepartment\CreativeDepartmentsResource;
use Illuminate\Http\Request;

class CreativeDepartments extends Controller
{
    //
    public function index(){
        $creativeDepartments = CreativeDepartments::with('translations')->get(); 
        return CreativeDepartmentsResource::collection($creativeDepartments); 
    }
}
