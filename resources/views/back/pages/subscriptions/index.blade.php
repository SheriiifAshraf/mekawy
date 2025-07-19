@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الإشتراكات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">جميع الإشتراكات</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a type="button" href="{{ route('subscriptions.create') }}" class="btn btn-primary px-5">
                    <i class="bx bx-cloud-upload mr-1"></i>إضافة إشتراك جديد
                </a>
            </div>

            <div class="col">
                <button class="btn btn-danger px-5" data-bs-toggle="modal" data-bs-target="#deleteAllSubscriptionsModal">
                    <i class="bx bx-trash mr-1"></i> حذف جميع الاشتراكات
                </button>
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

        <div class="modal fade" id="deleteAllSubscriptionsModal" tabindex="-1" aria-hidden="true">
            <form action="{{ route('subscriptions.destroyAll') }}" method="POST">
                @csrf
                {{-- @method('DELETE') --}}
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h5 class="modal-title text-white">تأكيد الحذف</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-white">
                            <h3 class="text-white">هل أنت متأكد من حذف <strong>جميع الاشتراكات</strong>؟ لا يمكن التراجع عن
                                هذا الإجراء!</h3>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-dark">نعم، احذف الكل</button>
                        </div>
                    </div>
                </div>
            </form>
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
                                <th>اسم الطالب</th>
                                <th>رقم الهاتف</th>
                                <th>اسم الكورس</th>
                                <th>سعر الكورس</th>
                                <th>حالة الدفع</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $uniqueSubscriptions = $subscriptions->unique(function ($value) {
                                    return $value->student->id . $value->course->id;
                                });
                            @endphp
                            @foreach ($uniqueSubscriptions as $index => $value)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $value->student->first_name . ' ' . $value->student->last_name }}</td>
                                    <td>{{ $value->student->phone }}</td>
                                    <td>{{ $value->course->name }}</td>
                                    <td>
                                        @if ($value->course->free)
                                            مجاني
                                        @else
                                            {{ $value->course->price }} ج.م
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->course->free)
                                            <span class="badge bg-secondary">مجاني</span>
                                        @else
                                            @if ($value->status == 1)
                                                <span class="badge bg-success">مدفوع</span>
                                            @else
                                                <span class="badge bg-danger">غير مدفوع</span>
                                            @endif
                                        @endif
                                        <button class="btn btn-sm btn-primary update-status" data-id="{{ $value->id }}"
                                            data-status="{{ $value->status }}">
                                            تغيير الحالة
                                        </button>
                                    </td>
                                    <td>
                                        <!-- Delete Button -->
                                        <a data-bs-toggle="modal" data-bs-target="#deleteModal{{ $value->id }}"
                                            class="btn btn-outline-danger">
                                            حذف الإشتراك
                                        </a>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $value->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('subscriptions.delete', $value->id) }}" method="POST">
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
                                                            <h3 class="text-white">هل أنت متأكد من حذف هذا الاشتراك؟</h3>
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
    @endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.update-status').click(function() {
                var id = $(this).data('id');
                var status = $(this).data('status');

                $.ajax({
                    url: '{{ route('subscriptions.updateStatus') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('حدث خطأ أثناء تحديث الحالة');
                        }
                    }
                });
            });
        });
    </script>
