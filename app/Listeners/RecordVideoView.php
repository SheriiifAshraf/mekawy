<?php

namespace App\Listeners;

use App\Events\VideoViewed;
use App\Models\VideosViewer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordVideoView implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\VideoViewed  $event
     * @return void
     */
    public function handle(VideoViewed $event)
    {
        $viewer = VideosViewer::firstOrNew(
            [
                'video_id' => $event->videoId,
                'student_id' => $event->studentId,
            ]
        );

        $viewer->seen = true;
        $viewer->view_count = $viewer->exists ? $viewer->view_count + 1 : 1;
        $viewer->save();
    }
}
