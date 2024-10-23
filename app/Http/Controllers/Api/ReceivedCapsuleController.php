<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReceivedCapsuleResource;
use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\ReceivedCapsule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ReceivedCapsuleController implements HasMiddleware
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
         $user = Auth::user();
         
         // Eager load images through the ReceivedCapsule model
         $capsules = ReceivedCapsule::with('images')
             ->where('receiver_email', $user->email)
             ->get();
             
         if ($capsules->isEmpty()) {
             return response()->json(['message' => 'No capsules found!'], 404);
         } else {
             return response()->json([
                 'data' => $capsules
             ], 200);
         }
     }
     
    /**
     * Show the form for creating a new resource.
     */
    public function create(){

    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Validate incoming request data
        $validatedData = $request->validate([
            'title' => 'required|max:50|string',
            'message' => 'required|max:500|string',
            'receiver_email' => 'required|email',
            'scheduled_open_at' => 'required',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Check if the user is authenticated
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Check if receiver exists in users table
        $receiver = User::where('email', $validatedData['receiver_email'])->first();
        if (!$receiver) {
            return response()->json(['message' => 'Receiver not found.'], 404);
        }
    
        // Step 1: Create the received capsule
        $createdCapsule = ReceivedCapsule::create(array_merge($validatedData, ['user_id' => $receiver->id]));

    
        // Step 2: Handle image uploads if images are provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile->isValid()) {
                    // Store the image and get the path
                    $imagePath = $imageFile->store('images', 'public');
    
                    // Create a new image record and associate it with the capsule
                    $image = new Image([
                        'image' => $imagePath,
                        'capsule_id' => $createdCapsule->id, // Use the ID of the created received capsule
                        'capsule_type' => 'App\\Models\\ReceivedCapsule'
                    ]);
    
                    // Save the image using the morphMany relationship
                    $createdCapsule->images()->save($image);
                }
            }
        }
    
        return response()->json([
            'data' => new ReceivedCapsuleResource($createdCapsule),
            'message' => 'Capsule sent successfully'
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
