<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Location\LocationInterface;
use App\Http\Resources\API\LocationListResource;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationInterface $locationI, private ResponseService $responseService)
    {
        $this->locationI = $locationI;
    }

    public function index(Request $request)
    {
        if (!$request->exists('order') || $request->order == null) {
            $request->merge(['order' => 'desc']);
        }
        if (!$request->exists('sort') || $request->sort == null) {
            $request->merge(['sort' => 'updated_at']);
        }
        $data = $this->locationI->models($request);
        if (!$data) {
            return $this->responseService->json('Fail!', [], 400);
        }
        if (!$data['status']) {
            return $this->responseService->json('Fail!', [], 400, $data['errors']);
        }
        $data = LocationListResource::collection($data['data']);
        return $this->responseService->json('Success!', $data, 200);
    }
}
