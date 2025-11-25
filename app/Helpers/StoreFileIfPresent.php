<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class StoreFileIfPresent
{
    /**
     * Store an uploaded file in public storage and return its full URL.
     *
     * @param UploadedFile $file  The uploaded file instance
     * @param string       $folder The folder inside storage/app/public
     *
     * @return string The public URL of the stored file
     */
    public static function store(UploadedFile $file, string $folder): string
    {
        // Store file in storage/app/public/{folder} and get the path
        $path = $file->store($folder, 'public');

        // Return the full public URL
        return asset('storage/' . $path);
    }
}
