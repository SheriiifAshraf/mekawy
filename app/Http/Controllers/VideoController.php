<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VideoRequest;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(Lesson $lesson)
    {
        $videos = $lesson->videos;
        return view('back.pages.videos.index', compact('lesson', 'videos'));
    }

    public function create(Lesson $lesson)
    {
        $videos = Video::all();
        return view('back.pages.videos.create', compact('lesson', 'videos'));
    }

    public function store(VideoRequest $request, Lesson $lesson)
    {
        $data = $request->validated();

        $durationMinutes = $request->input('duration_minutes', 0);

        $totalDurationInHours = $durationMinutes / 60;

        $data['duration'] = $totalDurationInHours;

        $video = $lesson->videos()->create($data);

        if ($request->hasFile('pdf')) {
            $video->addMedia($request->file('pdf'))->toMediaCollection('pdfs');
        }

        if ($request->hasFile('image')) {
            $video->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('lessons.videos.index', $lesson)->with('message', 'تم إضافة الفيديو بنجاح!');
    }


    public function edit(Video $video)
    {
        return view('back.pages.videos.edit', compact('video'));
    }

    public function update(VideoRequest $request, Video $video)
    {
        $data = $request->validated();

        $durationMinutes = $request->input('duration_minutes', 0);
        $totalDurationInHours = $durationMinutes / 60;
        $data['duration'] = $totalDurationInHours;

        $video->update($data);

        if ($request->hasFile('pdf')) {
            $video->clearMediaCollection('pdfs');
            $video->addMedia($request->file('pdf'))->toMediaCollection('pdfs');
        }

        if ($request->hasFile('image')) {
            $video->clearMediaCollection('images');
            $video->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('lessons.videos.index', $video->lesson_id)
            ->with('message', 'تم تحديث الفيديو بنجاح!');
    }



    public function delete(Video $video)
    {
        $lessonId = $video->lesson_id;
        $video->delete();
        return redirect()->route('lessons.videos.index', $lessonId)->with('message', 'تم حذف الفيديو بنجاح!');
    }

    public function showViewers($videoId)
    {
        $video = Video::with('lesson.course')->findOrFail($videoId);
        $course = $video->lesson->course;

        if ($course->free) {
            $students = Student::join('videos_viewers', 'students.id', '=', 'videos_viewers.student_id')
                ->where('videos_viewers.video_id', $video->id)
                ->select('students.id', 'students.first_name', 'students.middle_name', 'students.last_name', 'students.phone', 'students.father_phone', 'videos_viewers.view_count')
                ->get();
        } else {
            $subscribedStudents = $course->students()
                ->select('students.id', 'first_name', 'last_name', 'phone', 'father_phone')
                ->get();

            $viewers = $video->viewers()
                ->select('student_id', 'view_count')
                ->get()
                ->keyBy('student_id');

            $students = $subscribedStudents->map(function ($student) use ($viewers) {
                $student->view_count = $viewers[$student->id]->view_count ?? 0;
                return $student;
            });
        }

        return view('back.pages.videos.viewers', compact('students', 'video'));
    }
}
