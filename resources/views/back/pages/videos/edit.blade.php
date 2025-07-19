@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الفيديوهات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('lessons.videos.index', $video->lesson_id) }}">{{ $video->lesson->name }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل الفيديو</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!--end breadcrumb-->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">تعديل فيديو</h5>
                <form method="POST" action="{{ route('videos.update', $video->id) }}" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="input1" class="form-label">إسم الفيديو</label>
                        <input type="text" name="name" value="{{ $video->name }}" class="form-control"
                            id="input1">
                    </div>

                    <div class="col-md-6">
                        <label for="input2" class="form-label">رابط الفيديو</label>
                        <input type="url" name="link" value="{{ $video->link }}" class="form-control"
                            id="input2">
                    </div>

                    <div class="col-md-6">
                        <label for="duration_minutes" class="form-label">مدة الفيديو (بالدقائق)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $video->duration * 60) }}" class="form-control" id="duration_minutes" min="0">
                    </div>

                    <div class="col-md-6">
                        <label for="input3" class="form-label">ملف PDF</label>
                        <input type="file" name="pdf" class="form-control" id="input3">
                        @if ($video->getFirstMedia('pdfs'))
                            <div class="mt-3">
                                <p>Current PDF: {{ $video->getFirstMedia('pdfs')->file_name }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="input4" class="form-label">صورة</label>
                        <input type="file" name="image" class="form-control" id="input4">
                        @if ($video->getFirstMediaUrl('images'))
                            <div class="mt-3">
                                <img style="width: 200px; height: 200px; object-fit: cover;"
                                    src="{{ asset('storage/' . $video->getFirstMedia('images')->getAttribute('id') . '/' . $video->getFirstMedia('images')->file_name) }}"
                                    alt="Current Image" style="max-width: 100%; height: auto;">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="publish_at" class="form-label">توقيت النشر</label>
                        <input type="datetime-local" class="form-control" id="publish_at" name="publish_at"
                            value="{{ $video->publish_at->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary px-4">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
