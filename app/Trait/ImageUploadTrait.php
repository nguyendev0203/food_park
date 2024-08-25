<?php

namespace App\Trait;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use File;

Trait ImageUploadTrait
{
    /**
     * @param FormRequest|Request $request
     * @param string $inputName
     * @param string|null $oldPath
     * @param string $path
     * @return string
     */
    public function uploadImage(FormRequest|Request $request, string $inputName,string $oldPath = null, string $path = '/uploads'): ?string
    {
        if (!$request->hasFile($inputName)) {
            return null; // Explicitly handle the case where no file is uploaded
        }
    
        $file = $request->file($inputName); 
        $extension = $file->getClientOriginalExtension();
        $filename = 'media_' . uniqid() . '.' . $extension;
    
        $file->move(public_path($path), $filename); // Use Laravel's storage facade for better file handling

        // delete previous file if exist
        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        return $path . '/' . $filename;
    }

    /**
     * Undocumented function
     *
     * @param string $path
     * @return void
     */
    function removeImage(string $path): void
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
