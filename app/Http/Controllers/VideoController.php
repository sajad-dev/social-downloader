<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Http\Resources\VideoResource;
use App\Jobs\DownloadVideoJob;
use App\Models\Quality;
use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(new VideoResource(['video' => Video::all(), 'quality' => Quality::all()]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function download(VideoRequest $request)
    {
        $quality = $request->quality;
        DownloadVideoJob::dispatch($request->link, 'youtube', $quality);
        return response()->json(
            [
                'message' => 'Your Video Add To Queue',
                'status' => 200
            ]

        );

    }

}
