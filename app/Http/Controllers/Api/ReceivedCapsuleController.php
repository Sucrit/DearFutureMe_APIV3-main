<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\ReceivedCapsule;
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
    public function store(Request $request)
    {
        //
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
