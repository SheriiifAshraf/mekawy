@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">تعديل السؤال</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل السؤال</li>
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
                <h5 class="mb-4">تعديل السؤال</h5>
                <form method="POST" action="{{ route('questions.update', $question->id) }}" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <label for="question" class="form-label">السؤال</label>
                        <input name="question" class="form-control" id="question" value="{{ $question->question }}">
                    </div>

                    <div class="col-md-6">
                        <label for="explanation" class="form-label">التفسير</label>
                        <textarea name="explanation" rows="5" class="form-control" id="explanation">{{ $question->explanation }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="question_image" class="form-label">صورة السؤال</label>
                        <input type="file" name="question_image" class="form-control" id="question_image">
                        @if ($question->getFirstMedia('questions'))
                            <div class="mt-3">
                                <p>Current Question Image: {{ $question->getFirstMedia('questions')->file_name }}</p>
                                <img src="{{ asset('storage/' . $question->getFirstMedia('questions')->id . '/' . $question->getFirstMedia('questions')->file_name) }}"
                                     alt="Current Question Image"
                                     style="width: 200px; height: auto; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                    <hr>
                    <h4 class="">الإجابات</h4>

                    @foreach ($question->answers as $index => $answer)
                        <div class="col-md-6 mb-3"><hr>
                            <label for="answers[{{ $index }}][answer]" class="form-label">الإجابة {{ $index + 1 }}</label>
                            <input type="text" name="answers[{{ $index }}][answer]" class="form-control" id="answers[{{ $index }}][answer]" value="{{ $answer->answer }}">
                            <input type="file" name="answers[{{ $index }}][answer_image]" class="form-control mt-2">
                            @if ($answer->getFirstMedia('answers'))
                            <div class="mt-3">
                                {{-- <p>Current Answer Image: {{ $answer->getFirstMedia('answers')->file_name }}</p> --}}
                                <img src="{{ asset('storage/' . $answer->getFirstMedia('answers')->id . '/' . $answer->getFirstMedia('answers')->file_name) }}"
                                alt="Current Answer Image"
                                style="width: 200px; height: auto; object-fit: cover;">
                            </div>
                            @endif
                            <input type="checkbox" class="mt-2" name="answers[{{ $index }}][is_correct]" value="1" {{ $answer->is_correct ? 'checked' : '' }}> الإجابة الصحيحة
                        </div>
                    @endforeach

                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary px-4">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
