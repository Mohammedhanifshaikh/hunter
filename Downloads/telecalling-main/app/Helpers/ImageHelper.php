<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    public static function handleUploadedImage($file, $path, $delete = null)
    {
        if ($file) {
            if ($delete) {
                if (file_exists(storage_path('app/public/' . $path . '/' . $delete))) {
                    unlink(storage_path('app/public/' . $path . '/' . $delete));
                }
            }
            // Use timestamp for unique file naming
            $name = Carbon::now()->timestamp . '_' . $file->getClientOriginalName();
            $file->move(storage_path('app/public/' . $path), $name);
            return $name;
        }
    }

    public static function handleUpdatedUploadedImage($file, $path, $data, $delete_path, $field)
    {
        $name = time() . $file->getClientOriginalName();

        $file->move(storage_path('..') . $path, $name);
        if ($data[$field] != null) {
            if (file_exists(storage_path('app/public/') . $delete_path . $data[$field])) {
                unlink(storage_path('app/public/') . $delete_path . $data[$field]);
            }
        }
        return $name;
    }

    public static function handleDeletedImage($data, $field, $delete_path)
    {
        if (!empty($data[$field])) {
            $filename = basename($data[$field]);
            $filePath = storage_path('app/public/' . $delete_path . $filename);
            Log::info('Attempting to delete file at: ' . $filePath);

            if (file_exists($filePath)) {
                unlink($filePath);
                Log::info('File deleted successfully: ' . $filePath);
            } else {
                Log::warning('File not found for deletion: ' . $filePath);
            }
        }
    }
}
