@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الواجبات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الكورسات</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('courses.homeworks.index', $course) }}">{{ $course->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة واجب جديد</li>
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
                <h5 class="mb-4">إضافة واجب : {{ $course->name }}</h5>
                <form method="POST" action="{{ route('courses.homeworks.store', $course) }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم الواجب</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="">
                    </div>

                    <div class="col-md-6">
                        <label for="duration" class="form-label">المدة (بالدقيقة)</label>
                        <input type="number" name="duration" class="form-control" id="duration" placeholder="">
                    </div>

                    <div class="col-md-6">
                        <label for="from_date" class="form-label">من تاريخ</label>
                        <input type="datetime-local" name="from_date" class="form-control" id="from_date" placeholder="">
                    </div>

                

                    <div class="col-md-12">
                        <button type="button" class="btn btn-lg btn-danger px-5" data-bs-toggle="modal"
                            data-bs-target="#questionsModal">
                            <i class="lni lni-question-circle"></i>
                            إختر أسئلة الواجب
                        </button>
                        <!-- Modal for Selecting Questions -->
                        <div class="modal fade" id="questionsModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"> إختر الاسئلة
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <label for="questions" class="form-label">الأسئلة</label>
                                            <div id="questions" class="form-group">
                                                @foreach ($questions as $question)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="question_ids[]" value="{{ $question->id }}"
                                                            id="question-{{ $question->id }}">
                                                        <label class="form-check-label" for="question-{{ $question->id }}">
                                                            {{ $question->question }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">غلق</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">حفظ
                                            الاسئلة</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="instructions" class="form-label">التعليمات</label>
                        <textarea name="instructions" class="form-control" id="instructions" rows="3"></textarea>
                    </div>

                    {{-- <div class="col-md-6">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">اختر الحالة</option>
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div> --}}

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">تأكيد</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
