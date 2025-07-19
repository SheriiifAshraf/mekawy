<?php
namespace App\Http\Repositories\Media;

interface MediaInterface {
    public function upload($request);
    public function upload_medias ($request);
}
