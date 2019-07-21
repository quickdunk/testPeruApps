<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $s_folder = null, $s_disk = 'public', $s_filename = null)
    {
        $s_name = !is_null($s_filename) ? $s_filename : str_random(25);

        $o_file = $uploadedFile->storeAs($s_folder, $s_name.'.'.$uploadedFile->getClientOriginalExtension(), $s_disk);

        return $o_file;
    }
}
