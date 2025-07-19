<?php

namespace App\Http\Repositories\Location;

use App\Http\Repositories\Base\BaseRepository;
use App\Http\Repositories\Location\LocationInterface;
use App\Models\Location;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LocationRepository extends BaseRepository implements LocationInterface
{
    public $media;
    public function __construct(Location $model)
    {
        $this->model = $model;
    }
    public function models($request)
    {
        $models = $this->model->where(function ($query) use ($request) {
            if ($request->has('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($query) use ($request, $searchTerm) {
                    $query->Where('name', 'like', $searchTerm);
                });
            }
        });
        if ($request->exists('trashed') && $request->trashed !== null) {
            $models->onlyTrashed();
        }
        $models->with($request->with ?: [])
            ->withCount($request->withCount ?: []);

        [$sort, $order] = $this->setSortParams($request);
        $models->orderBy($sort, $order);
        $models = $request->per_page ? $models->paginate($request->per_page) : $models->get();
        return ['status' => true, 'data' => $models];
    }

}
