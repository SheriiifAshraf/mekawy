<?php

namespace App\Http\Repositories\Base;

use App\Http\Repositories\Base\BaseInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\Role;
use App\Models\Country;
use App\Models\Image;
use File;
use Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Str;

class BaseRepository implements BaseInterface
{
    public function __construct(public Model $model) {}

    public function findBySlug($slug)
    {
        $model = $this->model->where('slug', $slug)->first();
        return $model ?? false;
    }

    public function findByName($name)
    {
        $model = $this->model->where('name', $name)->first();
        return $model ?? false;
    }

    public function findById($id)
    {
        $model = $this->model->find($id);
        return $model ?? false;
    }

    public function findWhere($where)
    {
        $model = $this->model->where($where)->first();
        return $model ?? false;
    }

    public function findByWhere($column, $value, $where = [], $whereNot = [])
    {
        $model = $this->model->where($column, $value)->where($where)->whereNot($whereNot)->first();
        return $model ?? false;
    }

    public function findByWith($column, $value, $request, $where = [], $whereNot = [])
    {
        $model = $this->model->where($column, $value)->with($request->with ?? [])->withCount($request->withCount ?? [])->where($where)->whereNot($whereNot)->first();
        return $model ?? false;
    }

    public function findByIdWith($request)
    {
        $model = $this->model->where('id', $request->id)->with($request->with ?? [])->withCount($request->withCount ?? [])->first();
        return $model ?? false;
    }

    public function delete($id)
    {
        $model = $this->findById($id);

        if (!$model) {
            return ['status' => false, 'errors' => ['error' => trans('messages.notfound')]];
        }

        // if (($model instanceof Admin && $model->id == 1) || ($model instanceof Role && $model->id == 1) || ($model instanceof Country && $model->id == 1) || ($model instanceof City && $model->id == 1) || ($model instanceof Category && $model->id == 1)) {
        //     return ['status' => false, 'errors' => ['error' => [trans('crud.protected')]]];
        // }

        // if (isset($this->model::$cascade)) {
        //     foreach ($this->model::$cascade as $cascade) {
        //         if ($model->$cascade && $count = $model->$cascade->count()) {
        //             return ['status' => false, 'errors' => ['error' => [trans('auth.cascade_delete', ['model' => trans_class_basename($model), 'count' => $count, 'cascade' => trans('models.' . $cascade)])]]];
        //         }
        //     }
        // }

        $model->delete();
        return ['status' => true, 'data' => []];
    }

    public function deletePulk($ids)
    {
        $models = $this->model->whereIn('id', $ids);
        if (!$models) {
            return ['status' => false];
        }

        foreach ($ids as $id) {
            $model = $this->delete($id);
            if (!$model || !$model['status']) {
                return $model;
            }
        }
        return ['status' => true, 'data' => []];
    }

    public function setLocales($model, $locales, $slug = false, $generatedSlug = null)
    {
        foreach ($locales as $lang => $locale) {
            if ($slug && $lang && $lang == 'en' && $locale['name']) {
                switch (get_class($model)) {
                    case Role::class:
                        $model->update([
                        'name' => $generatedSlug ? $generatedSlug : Str::slug($locale['name']),
                        ]);
                        break;

                    default:
                        $model->update([
                        'slug' => $generatedSlug ? $generatedSlug : Str::slug($locale['name']),
                        ]);
                        break;
                }
            }

            $model->locales()->updateOrCreate(['locale' => $lang], $locale);
        }
    }

    public function setSortParams($request)
    {
        switch ($request->sort) {
            default:
                $sort = $request->sort ?: 'created_at';
                break;
        }

        switch ($request->order) {
            default:
                $order = $request->order ?: 'desc';
                break;
        }

        return [$sort, $order];
    }

    public function getWith($models, $with)
    {
        return $models->with($with);
    }

    public function getWithCount($models, $withCount)
    {
        return $models->withCount($withCount);
    }

    public function generateSlug($model, $name, $column = 'slug')
    {
        $slug = Str::slug($name);
        $count = 1;

        while ($model->where($column, $slug)->get()->count()) {
            $slug = Str::slug($name) . '-' . $count++;
        }
        return $slug;
    }

    public function uploadImages($imageFile, $dir)
    {
        $name = md5(uniqid()) . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->storeAs($dir, $name);
        return 'storage/' . $dir . "/" . $name;
    }

    public function getIPAddress()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function generatefrom($name)
    {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $baseSlug = $slug;
        $count = 1;
        while ($this->model->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }

    public function UpdateImage ($request,$model){
        $imageIds = $request->images;
        $existingImageIds = $model->media()->pluck('id')->toArray();
        $model->media()->whereNotIn('id', $imageIds)->delete();
        $newImageIds = array_diff($imageIds, $existingImageIds);
        return $newImageIds;
}
    public function set_History($model, $user, $desc){
            activity()
            ->performedOn($model)
            ->causedBy($user)
            ->log($desc);
        }
}

