<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\ProfilePasswordUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;
use App\Trait\ImageUploadTrait;

class ProfileController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        return view('admin.profile.index');
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $imagePath = $this->uploadImage($request, 'avatar');
        $user->name = $request->name;
        $user->avatar = isset($imagePath) ? $imagePath : $user->avatar;
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
}
