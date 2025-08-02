@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">مشاهدات الفيديو</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">مشاهدات فيديو {{ $video->name }}</li>
                    </ol>
                </nav>
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
                    <table id="example" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>الاسم بالكامل</th>
                                <th>رقم الهاتف</th>
                                <th>رقم ولي الأمر</th>
                                <th>عدد مرات المشاهدة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr class="{{ $student->view_count > 0 ? '' : 'table-warning' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ $student->father_phone }}</td>
                                    <td>
                                        @if ($student->view_count > 0)
                                            {{ $student->view_count }}
                                        @else
                                            <span class="text-danger">لم يشاهد</span>
                                        @endif
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
