<?php

namespace App\Trait;

use Illuminate\Foundation\Http\FormRequest;

Trait ImageUploadTrait
{
    /**
     * @param FormRequest $request
     * @param string $inputName
     * @param string $path
     * @return string
     */
    public function uploadImage(FormRequest $request, string $inputName, string $path = '/uploads'): ?string
    {
        if (!$request->hasFile($inputName)) {
            return null; // Explicitly handle the case where no file is uploaded
        }
    
        $file = $request->file($inputName); 
        $extension = $file->getClientOriginalExtension();
        $filename = 'media_' . uniqid() . '.' . $extension;
    
        $file->move(public_path($path), $filename); // Use Laravel's storage facade for better file handling
    
        return $path . '/' . $filename;
    }
}
