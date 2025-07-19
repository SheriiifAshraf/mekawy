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
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الكورسات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $course->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a type="button" href="{{ route('courses.lessons.create', $course->id) }}" class="btn btn-primary px-5">
                    <i class="bx bx-cloud-upload mr-1"></i>إضافة درس جديد
                </a>
            </div>
        </div>
        @if (Session::has('message'))
            <script>
                swal("رسالة!", "{{ Session::get('message') }}", 'success', {
                    button: true,
                    button: "OK",
                    timer: 12000,
                });
            </script>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>إسم الدرس</th>
                                <th>محتوي الدرس</th>
                                <th>ملحقات الدرس</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $uniqueLessons = $lessons->unique('name');
                            @endphp
                            @foreach ($uniqueLessons as $lesson)
                                <tr>
                                    <td>{{ $lesson->name }}</td>
                                    <td>{{ $lesson->description }}</td>
                                    <td>
                                        <a href="{{ route('lessons.videos.index', $lesson->id) }}" type="button"
                                            class="btn btn-warning px-5 radius-30">
                                            <i class="bx bx-right-arrow mr-1"></i>فيديوهات الدرس
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-outline-warning">
                                            <i class='bx bx-edit me-0'></i>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#danger{{ $lesson->id }}"
                                            class="btn btn-outline-danger">
                                            <i class='bx bx-trash me-0'></i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="danger{{ $lesson->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('lessons.delete', $lesson->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content bg-danger">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-white">تأكيد الحذف</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-white">
                                                            <h3 class="text-white">هل أنت متأكد من حذف هذا الدرس؟</h3>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-dark">حذف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
