@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الأكواد</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('codes.index') }}">الأكواد</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة كود جديد</li>
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
                <h5 class="mb-4">إضافة كود</h5>
                <form method="POST" action="{{ route('codes.store') }}" class="row g-3">
                    @csrf
                    {{-- <div class="col-md-6">
                        <label for="student_id" class="form-label">اختيار الطالب</label>
                        <select name="student_id" id="student_id" class="form-control">
                            <option value="">اختيار الطالب</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->first_name . ' ' . $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-6">
                        <label for="period" class="form-label">مدة صلاحية الكود (بالأيام)</label>
                        <input type="text" class="form-control" id="period" name="period" value="{{ old('period') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">نوع الكود</label>
                        <select name="type" id="type" class="form-control" onchange="handleTypeChange()">
                            <option value="">اختيار نوع الكود</option>
                            <option value="course">كورس</option>
                            <option value="lesson">درس</option>
                            <option value="video">فيديو</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="code_count" class="form-label">عدد الأكواد التي تريد إنشائها</label>
                        <input type="number" class="form-control" id="code_count" name="code_count" min="1" value="{{ old('code_count', 1) }}">
                    </div>


                    <!-- Course Select -->
                    <div class="col-md-6 d-none" id="course_select">
                        <label for="course_id" class="form-label">اختيار الكورس</label>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">اختيار الكورس</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lesson Select -->
                    <div class="col-md-6 d-none" id="lesson_select">
                        <label for="lesson_id" class="form-label">اختيار الدرس</label>
                        <select name="lesson_id" id="lesson_id" class="form-control">
                            <option value="">اختيار الدرس</option>
                            @foreach ($lessons as $lesson)
                                <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Video Select -->
                    <div class="col-md-6 d-none" id="video_select">
                        <label for="video_id" class="form-label">اختيار الفيديو</label>
                        <select name="video_id" id="video_id" class="form-control">
                            <option value="">اختيار الفيديو</option>
                            @foreach ($videos as $video)
                                <option value="{{ $video->id }}">{{ $video->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">تأكيد</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleTypeChange() {
            var type = document.getElementById('type').value;
            document.getElementById('course_select').classList.add('d-none');
            document.getElementById('lesson_select').classList.add('d-none');
            document.getElementById('video_select').classList.add('d-none');

            if (type === 'course') {
                document.getElementById('course_select').classList.remove('d-none');
            } else if (type === 'lesson') {
                document.getElementById('lesson_select').classList.remove('d-none');
            } else if (type === 'video') {
                document.getElementById('video_select').classList.remove('d-none');
            }
        }
    </script>
@endsection
