<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfilePasswordUpdateRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;
use App\Trait\ImageUploadTrait;

class ProfileController extends Controller
{
    use ImageUploadTrait;
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        toastr('Profile updated successfully', 'success');

        return redirect()->back();
    }

    public function updatePassword(ProfilePasswordUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->password = bcrypt($request->password);
        $user->save();
        toastr('Password updated successfully', 'success');
        return redirect()->back();
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $imagePath = $this->uploadImage($request, 'avatar');
        $user->avatar = isset($imagePath) ? $imagePath : $user->avatar;
        $user->save();
        
        return response(['status' => 'success', 'message' => 'Avatar updated successfully'], 200);
    }
}
