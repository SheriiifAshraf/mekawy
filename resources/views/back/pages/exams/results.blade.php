@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">نتيجة إمتحان</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        {{-- <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الكورسات</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">{{ $exam->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">درجة الامتحان: {{ $exam_marks }}</h5>
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الطالب</th>
                                <th>درجة الطالب</th>
                                <th>رقم الهاتف</th>
                                <th>رقم هاتف الأب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attempts->unique('student_id') as $key => $attempt)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attempt->student->first_name }} {{ $attempt->student->last_name }}</td>
                                    <td>{{ $attempt->marks }}</td>
                                    <td>{{ $attempt->student->phone }}</td>
                                    <td>{{ $attempt->student->father_phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('back/assets/js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                //buttons: ['copy', 'excel', 'pdf', 'print']
                buttons: ['excel']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
