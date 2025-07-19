@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الطلاب</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">جميع الطلاب</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a href="{{ route('students.create') }}" class="btn btn-primary px-5">
                    <i class="bx bx-cloud-upload mr-1"></i> إضافة طالب جديد
                </a>
            </div>
            <div class="col">
                <button class="btn btn-danger px-5" data-bs-toggle="modal" data-bs-target="#deleteAllStudentsModal">
                    <i class="bx bx-trash mr-1"></i> حذف جميع الطلاب
                </button>
            </div>
        </div>

        <div class="modal fade" id="deleteAllStudentsModal" tabindex="-1" aria-hidden="true">
            <form action="{{ route('students.destroyAll') }}" method="POST">
                @csrf
                {{-- @method('DELETE') --}}
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h5 class="modal-title text-white">تأكيد الحذف</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-white">
                            <h3 class="text-white">هل أنت متأكد من حذف <strong>جميع الطلاب</strong>؟ لا يمكن التراجع عن هذا
                                الإجراء!</h3>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-dark">نعم، احذف الكل</button>
                        </div>
                    </div>
                </div>
            </form>
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

        @if (Session::has('message'))
            <script>
                swal("رسالة!", "{{ Session::get('message') }}", 'success', {
                    button: true,
                    button: "OK",
                    timer: 12000,
                });
            </script>
        @endif

        @if (Session::has('error'))
            <script>
                swal("خطأ!", "{{ Session::get('error') }}", 'error', {
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
                                <th>الاسم الأول</th>
                                <th>الاسم الأخير</th>
                                <th>رقم الهاتف</th>
                                <th>رقم هاتف الأب</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->first_name }}</td>
                                    <td>{{ $student->last_name }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ $student->father_phone }}</td>
                                    <td>

                                        <button class="btn btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#changePasswordModal{{ $student->id }}">
                                            تغيير كلمة المرور
                                        </button>

                                        {{-- Modal for changing password --}}
                                        <div class="modal fade" id="changePasswordModal{{ $student->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تغيير كلمة المرور</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('students.changePassword', $student->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="password" class="form-label">كلمة المرور
                                                                    الجديدة</label>
                                                                <input type="password" class="form-control" id="password"
                                                                    name="password" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="password_confirmation" class="form-label">تأكيد
                                                                    كلمة المرور</label>
                                                                <input type="password" class="form-control"
                                                                    id="password_confirmation" name="password_confirmation"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-dark">تحديث</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- New combined modal button --}}
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#studentDetailsModal{{ $student->id }}">
                                            عرض التفاصيل
                                        </button>

                                        {{-- Modal for combined details: Courses, Homeworks, Exams --}}
                                        <div class="modal fade" id="studentDetailsModal{{ $student->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تفاصيل الطالب: {{ $student->first_name }}
                                                            {{ $student->last_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{-- Tabs for Courses, Homeworks, Exams --}}
                                                        <ul class="nav nav-tabs" id="myTab{{ $student->id }}"
                                                            role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link active"
                                                                    id="courses-tab{{ $student->id }}"
                                                                    data-bs-toggle="tab"
                                                                    href="#courses{{ $student->id }}" role="tab"
                                                                    aria-controls="courses"
                                                                    aria-selected="true">الكورسات</a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="homeworks-tab{{ $student->id }}"
                                                                    data-bs-toggle="tab"
                                                                    href="#homeworks{{ $student->id }}" role="tab"
                                                                    aria-controls="homeworks"
                                                                    aria-selected="false">الواجبات</a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="exams-tab{{ $student->id }}"
                                                                    data-bs-toggle="tab" href="#exams{{ $student->id }}"
                                                                    role="tab" aria-controls="exams"
                                                                    aria-selected="false">الامتحانات</a>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content" id="myTabContent{{ $student->id }}">
                                                            {{-- Courses Tab --}}
                                                            <div class="tab-pane fade show active"
                                                                id="courses{{ $student->id }}" role="tabpanel"
                                                                aria-labelledby="courses-tab{{ $student->id }}">
                                                                <h5 class="mt-4">الكورسات المشترك فيها:</h5>
                                                                @if ($student->courses->isEmpty())
                                                                    <p>الطالب ليس مشتركًا في أي كورسات</p>
                                                                @else
                                                                    <ul class="list-group">
                                                                        @foreach ($student->courses as $course)
                                                                            <li class="list-group-item">
                                                                                <strong>{{ $course->name }}</strong>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>

                                                            {{-- Homework Tab --}}
                                                            <div class="tab-pane fade" id="homeworks{{ $student->id }}"
                                                                role="tabpanel"
                                                                aria-labelledby="homeworks-tab{{ $student->id }}">
                                                                <h5 class="mt-4">الواجبات التي تم تسليمها:</h5>
                                                                @if ($student->homeworkAttempts->isEmpty())
                                                                    <p>لم يتم تسليم أي واجبات</p>
                                                                @else
                                                                    <ul class="list-group">
                                                                        @foreach ($student->homeworkAttempts as $attempt)
                                                                            <li class="list-group-item">
                                                                                <strong>الواجب:
                                                                                    {{ $attempt->homework->name }}</strong>
                                                                                => الدرجة: {{ $attempt->marks }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>

                                                            {{-- Exam Tab --}}
                                                            <div class="tab-pane fade" id="exams{{ $student->id }}"
                                                                role="tabpanel"
                                                                aria-labelledby="exams-tab{{ $student->id }}">
                                                                <h5 class="mt-4">الامتحانات التي تم أداؤها:</h5>
                                                                @if ($student->examAttempts->isEmpty())
                                                                    <p>لم يتم أداء أي امتحانات</p>
                                                                @else
                                                                    <ul class="list-group">
                                                                        @foreach ($student->examAttempts as $attempt)
                                                                            <li class="list-group-item">
                                                                                <strong>الامتحان:
                                                                                    {{ $attempt->exam->name }}</strong>
                                                                                => الدرجة: {{ $attempt->marks }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-dismiss="modal">إغلاق</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- حذف الطالب --}}
                                        <a data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}"
                                            class="btn btn-outline-danger">
                                            <i class='bx bx-trash me-0'></i>
                                        </a>

                                        {{-- Delete Modal --}}
                                        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content bg-danger">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-white">تأكيد الحذف</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-white">
                                                            <h3 class="text-white">هل أنت متأكد من حذف الطالب
                                                                {{ $student->first_name }} {{ $student->last_name }} ؟
                                                            </h3>
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
