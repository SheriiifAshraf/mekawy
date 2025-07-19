@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">إضافة سؤال جديد</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة سؤال جديد</li>
                    </ol>
                </nav>
            </div>
        </div>

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
                <h5 class="mb-4">إضافة سؤال</h5>
                <form method="POST" action="{{ route('questions.store') }}" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label for="question" class="form-label">السؤال</label>
                        <input name="question" class="form-control" id="question">
                    </div>

                    <div class="col-md-6">
                        <label for="explanation" class="form-label">التفسير</label>
                        <textarea name="explanation" class="form-control" id="explanation"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="question_image" class="form-label">صورة السؤال</label>
                        <input type="file" name="question_image" class="form-control" id="question_image">
                    </div>
                    <hr>
                    <h5 class="mt-4">الإجابات</h5>

                    @for ($i = 0; $i < 4; $i++)
                        <div class="col-md-6 mb-3">
                            <hr>
                            <label for="answers[{{ $i }}][answer]" class="form-label">الإجابة
                                {{ $i + 1 }}</label>
                            <input type="text" name="answers[{{ $i }}][answer]" class="form-control"
                                id="answers[{{ $i }}][answer]">
                                <input type="file" name="answers[{{ $i }}][answer_image]"
                                class="form-control mt-2">
                                <input type="checkbox" class="mt-2" name="answers[{{ $i }}][is_correct]"
                                    value="1"> الإجابة الصحيحة
                        </div>
                    @endfor

                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary px-4">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
