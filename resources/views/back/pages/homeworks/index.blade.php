@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الواجبات</div>
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
        <!-- End Breadcrumb -->

        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a href="{{ route('courses.homeworks.create', $course->id) }}" class="btn btn-primary px-5"> <i
                        class="bx bx-cloud-upload mr-1"></i>إضافة واجب جديد</a>
            </div>
        </div>

        <!-- Notifications -->
        @if (Session::has('message'))
            <script>
                swal("رسالة!", "{{ Session::get('message') }}", 'success', {
                    button: true,
                    button: "OK",
                    timer: 12000,
                });
            </script>
        @endif
        <!-- End Notifications -->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">الواجبات لكورس: {{ $course->name }}</h5>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>المدة (بالدقيقة)</th>
                                <th>من تاريخ</th>
                                <th>أسئلة الواجب</th>
                                <th>عرض نتائج</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $uniqueHomeworks = $homeworks->unique('name');
                            @endphp
                            @foreach ($uniqueHomeworks as $homework)
                                <tr>
                                    <td>{{ $homework->name }}</td>
                                    <td>{{ $homework->duration }} دقيقة</td>
                                    <td>{{ $homework->from_date }}</td>
                                    {{-- <td>{{ $homework->status ? 'نشط' : 'غير نشط' }}</td> --}}
                                    <td>
                                        <a href="{{ route('homeworks.questions', $homework->id) }}" target="_blank"
                                            class="btn btn-outline-danger">
                                            <i class='lni lni-download'></i> تنزيل الواجب
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('homeworks.results', $homework->id) }}"
                                            class="btn btn-outline-info">
                                            <i class='bx bx-bar-chart-alt me-0'></i> عرض النتائج
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('homeworks.edit', $homework->id) }}"
                                            class="btn btn-outline-warning"><i class='bx bx-edit me-0'></i>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#danger{{ $homework->id }}"
                                            class="btn btn-outline-danger"><i class='bx bx-trash me-0'></i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="danger{{ $homework->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('homeworks.destroy', $homework->id) }}" method="POST">
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
                                                            <h3 class="text-white">هل أنت متأكد من حذف هذا الواجب</h3>
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
