<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function userProfile(Request $request, User $user)
    {
        $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // example validation rules
        ]);

        $image = $request->file('image');
        $path = $image->store('image');

    }
}
