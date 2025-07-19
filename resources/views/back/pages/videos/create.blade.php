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
                                href="{{ route('lessons.videos.index', $lesson->id) }}">{{ $lesson->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة فيديو جديد</li>
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
                <h5 class="mb-4">إضافة فيديو جديد</h5>
                <form method="POST" action="{{ route('lessons.videos.store', $lesson->id) }}" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="input1" class="form-label">إسم الفيديو</label>
                        <input type="text" name="name" class="form-control" id="input1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="input2" class="form-label">رابط الفيديو</label>
                        <input type="url" name="link" class="form-control" id="input2">
                    </div>

                    <div class="col-md-6">
                        <label for="duration" class="form-label">مدة الفيديو (الدقائق)</label>
                        <input type="number" name="duration_minutes" class="form-control" id="duration" placeholder="أدخل المدة بالدقائق" min="0" required>
                    </div>


                    <div class="col-md-6">
                        <label for="input3" class="form-label">ملف PDF</label>
                        <input type="file" name="pdf" class="form-control" id="input3" accept="application/pdf">
                    </div>

                    <div class="col-md-6">
                        <label for="input4" class="form-label">صورة</label>
                        <input type="file" name="image" class="form-control" id="input4" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label for="input5" class="form-label">اختر فيديو موجود</label>
                        <select name="existing_link" class="form-control" id="input5">
                            <option value="">-- اختر رابط الفيديو --</option>
                            @foreach ($videos->unique('link') as $video)
                                <option value="{{ $video->link }}">{{ $video->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="publish_at" class="form-label">توقيت النشر</label>
                        <input type="datetime-local" class="form-control" id="publish_at" name="publish_at" required>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary px-4">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
