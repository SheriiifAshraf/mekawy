@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الدروس</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('courses.lessons.index', $lesson->course_id) }}">الدروس</a></li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل الدرس</li>
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
                <h5 class="mb-4">تعديل درس</h5>
                <form method="POST" action="{{ route('lessons.update', $lesson->id) }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="input1" class="form-label">إسم الدرس</label>
                        <input type="text" name="name" value="{{ $lesson->name }}" class="form-control"
                            id="input1">
                    </div>

                    <div class="col-md-6">
                        <label for="input2" class="form-label">محتوي الدرس</label>
                        <textarea class="form-control" name="description" id="input2" rows="3">{{ $lesson->description }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">تأكيد</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
