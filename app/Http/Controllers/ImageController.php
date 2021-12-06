<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public function showImageProfile($id) {
        $current_user_id = $_SESSION['user_id'];
        if (Persona::haveAdministratorPermission($current_user_id)) {
            $file_name = 'profile_image.jpg';
            return response()->file(storage_path('uploads/' . $id . '/' . $file_name));
        }
        return false;
    }
}
