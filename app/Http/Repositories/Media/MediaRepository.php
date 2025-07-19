<?php
namespace App\Http\Repositories\Media;
use App\Http\Repositories\Base\BaseRepository;
use App\Http\Resources\MediaResource;
use App\Models\Image;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\FileUploader;
use Str;
use DB;
use App\Models\File;
use Ramsey\Uuid\Uuid;

class MediaRepository extends BaseRepository implements MediaInterface
{

    public function __construct(Media $model){
        $this->model = $model;
    }
    public function upload($request)
    {
        $media = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $media = Image::create()->addMedia($file)->usingFileName($fileName)->toMediaCollection('images');
        }
        if (!$media) {
            return false;
        }
        return ['status' => true, 'data' => $media];
    }

    public function upload_medias($request) {
    $mediaCollection = collect();
    if ($request->hasFile('images')) {
        $files = $request->file('images');
        foreach ($files as $file) {
            $fileName = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
            $media = Image::create()->addMedia($file)->usingFileName($fileName)->toMediaCollection('images');
            $mediaCollection->push($media);
        }
    }
    if (!$media) {
            return false;
        }
    return ['status' => true, 'data' => $mediaCollection];
}
}

