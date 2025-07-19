<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Media\MediaInterface;
use App\Http\Requests\CreateMediaRequest;
use App\Http\Requests\CreateMediasRequest;
use App\Http\Resources\MediaResource;
use App\Services\ResponseService;

class MediaController extends Controller
{
    public $MediaI;
    public function __construct(MediaInterface $MediaI, public ResponseService $responseService){
        $this->MediaI = $MediaI;
    }
    public function upload(CreateMediaRequest $request)
    {
        $document = $this->MediaI->upload($request);

        if (!$document) {
            return $this->responseService->json('Fail!', [], 400, ['error' => trans('messages.error')]);
        }

        if (!$document['status']) {
            return $this->responseService->json('Fail!', [], 400, $document['errors']);
        }
        $data = new MediaResource($document['data']);
        return $this->responseService->json('Success!', $data, 200);
    }

    public function upload_medias(CreateMediasRequest $request)
    {
        $document = $this->MediaI->upload_medias($request);

        if (!$document) {
            return $this->responseService->json('Fail!', [], 400, ['error' => trans('messages.error')]);
        }

        if (!$document['status']) {
            return $this->responseService->json('Fail!', [], 400, $document['errors']);
        }
        $data =  MediaResource::collection($document['data']);
        return $this->responseService->json('Success!', $data, 200);
    }
}
