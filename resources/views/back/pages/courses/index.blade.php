@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الكورسات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">جميع الكورسات</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a type="button" href="{{ url('courses/create') }}" class="btn btn-primary px-5">
                    <i class="bx bx-cloud-upload mr-1"></i>إضافة كورس جديد
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
                                <th>صورة الكورس</th>
                                <th>إسم الكورس</th>
                                <th>ملحقات الكورس</th>
                                <th>العمليات</th>
                                <th>إجراءات إضافية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>
                                        @if ($course->image)
                                            <img src="{{ $course->image }}" alt="course image" width="100">
                                        @else
                                            <span>لا يوجد صورة</span>
                                        @endif
                                    </td>
                                    <td>{{ $course->name }}</td>
                                    <td>
                                        @if ($course->free)
                                            مجاني
                                        @else
                                            {{ $course->price }} ج.م
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('courses.lessons.index', $course->id) }}"
                                            class="btn btn-outline-primary px-5 radius-30">الدروس</a>
                                        <a href="{{ route('courses.homeworks.index', $course->id) }}"
                                            class="btn btn-outline-success px-5 radius-30">الواجبات</a>
                                        <a href="{{ route('courses.exams.index', $course->id) }}"
                                            class="btn btn-outline-danger px-5 radius-30">الإمتحانات</a>
                                        <button class="btn btn-outline-info px-5 radius-30" data-bs-toggle="modal"
                                            data-bs-target="#studentsModal{{ $course->id }}">المشتركون</button>
                                    </td>
                                    <td>
                                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-warning">
                                            <i class='bx bx-edit me-0'></i>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#danger{{ $course->id }}"
                                            class="btn btn-outline-danger">
                                            <i class='bx bx-trash me-0'></i>
                                        </a>

                                        <div class="modal fade" id="danger{{ $course->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('courses.delete', $course->id) }}" method="POST">
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
                                                            <h3 class="text-white">هل أنت متأكد من حذف هذا الكورس؟</h3>
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

                    @foreach ($courses as $course)
                        <div class="modal fade" id="studentsModal{{ $course->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">الطلاب المشتركين في {{ $course->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>عدد الطلاب: <strong>{{ $course->subscriptions_count }}</strong></p>

                                        @if ($course->subscriptions_count > 0)
                                            <div class="table-responsive mt-3">
                                                <table class="table table-striped table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>اسم الطالب</th>
                                                            <th>رقم الهاتف</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($course->subscriptions as $index => $sub)
                                                            @if ($sub->student)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $sub->student->first_name }}
                                                                        {{ $sub->student->last_name }}</td>
                                                                    <td>{{ $sub->student->phone }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">لا يوجد طلاب مشتركين حاليًا.</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"
                                            data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
