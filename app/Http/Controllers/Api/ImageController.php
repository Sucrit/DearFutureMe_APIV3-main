<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Image;
use App\Models\Capsule;
use Illuminate\Http\Request;
use App\Models\ReceivedCapsule;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Resources\ReceivedCapsuleResource;
use Illuminate\Routing\Controllers\HasMiddleware;

class ImageController implements HasMiddleware
{   
    /**
     * Display a listing of the resource.
     */

     public static function middleware()
     {
         return [
             new Middleware('auth:sanctum')
         ];
     }
     public function index()
     {
    
     }
     
    /**
     * Show the form for creating a new resource.
     */
    public function create(){

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Capsule $capsule) {
        // Validate incoming request data
        $validatedData = $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // $capsules = Capsule::where('id', $capsule)->get();
        if(!$capsule){
            return response()->json([
                'message' => 'Capsule not found'
            ]);
        }
        // Handle image uploads if provided
        $uploadedImages = [];
        foreach ($request->file('images') as $imageFile) {
            if ($imageFile->isValid()) {
                $imagePath = $imageFile->store('images', 'public');

                $image = new Image([
                    'image' => $imagePath,
                    'capsule_id' => $capsule->id,
                    'capsule_type' => Capsule::class
                ]);
                $image->save();

                // $uploadedImages[] = new ImageResource($image);
            }
        }

        return response()->json([
            'message' => 'Images added successfully',
        ], 201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Capsule $capsule) {
        //
    }    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the image by ID
        $image = Image::find($id);
    
        // Check if the image exists
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        // Check if the image is associated with a specific capsule type
        if ($image->capsule_type !== Capsule::class) {
            return response()->json(['message' => 'Image not associated with this capsule'], 403);
        }
    
        // Delete the image file from storage
        Storage::disk('public')->delete($image->image);
    
        // Delete the image record from the database
        $image->delete();
    
        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}    
