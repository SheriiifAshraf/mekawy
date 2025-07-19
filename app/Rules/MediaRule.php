<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Image;

class MediaRule implements Rule
{
    private $type;
    private $id;

    public function __construct($type = null, $id = null)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        $media = Media::find($value);
        if (!$media || !is_numeric($value)) {
            return false;
        }

        if ($this->type === null) {
            return true;
        }
return ($media->model_type === Image::class || (($media->model_type)  == $this->type && $media->model_id == $this->id));}

    public function message()
    {
        return 'The media is assigned to another model.';
    }
}
