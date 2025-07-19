@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">عرض الأكواد</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('codes.index') }}">الأكواد</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">عرض أكواد

                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الكود</th>
                                {{-- <th>نوع الكود</th> --}}
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($codes as $index => $code)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $code->code }}</td>
                                    {{-- <td>{{ $code->type }}</td> --}}
                                    <td>
                                        <a href="{{ route('codes.show', $code->id) }}"
                                            class="btn btn-outline-success px-5 radius-30">عرض</a>

                                        <a data-bs-toggle="modal" data-bs-target="#danger{{ $code->id }}"
                                            class="btn btn-outline-danger">
                                            <i class='bx bx-trash me-0'></i>
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="danger{{ $code->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('codes.delete', $code->id) }}" method="POST">
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
                                                            <h3 class="text-white">هل أنت متأكد من حذف هذا الكود؟</h3>
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
