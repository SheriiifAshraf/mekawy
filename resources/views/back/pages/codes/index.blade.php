@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الأكواد</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('codes.index') }}">الأكواد</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a type="button" href="{{ route('codes.create') }}" class="btn btn-primary px-5">
                    <i class="bx bx-cloud-upload mr-1"></i>إضافة كود جديد
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
                                <th>#</th>
                                <th>مدة صلاحية الأكواد (بالأيام)</th>
                                <th>عدد الأكواد</th>
                                <th>تاريخ الإضافة</th>
                                <th>عرض الأكواد</th>
                                <th>حذف الأكواد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batches as $index => $batch)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $batch->period }}</td>
                                    <td>{{ $batch->count }}</td>
                                    <td>{{ $batch->created_at }}</td>
                                    <td>
                                        <a href="{{ route('codes.batch', ['batch_id' => $batch->batch_id]) }}"
                                           class="btn btn-outline-success px-5 radius-30">عرض الأكواد</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('codes.deleteBatch', $batch->batch_id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف جميع الأكواد؟');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger px-5 radius-30">حذف المجموعة</button>
                                        </form>
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
