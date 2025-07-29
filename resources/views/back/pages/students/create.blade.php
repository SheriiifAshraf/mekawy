@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الطلاب</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">قائمة الطلاب</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة طالب</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf

                    <!-- الاسم الكامل -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>الاسم الأول</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>الاسم الثاني</label>
                            <input type="text" name="middle_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>الاسم الأخير</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>

                    <!-- أرقام الهواتف -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>رقم هاتف الأب</label>
                            <input type="text" name="father_phone" class="form-control" required>
                        </div>
                    </div>

                    <!-- الموقع والمرحلة والصف -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>الموقع</label>
                            <select name="location_id" class="form-select" required>
                                <option value="">اختر الموقع</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>المرحلة التعليمية</label>
                            <select name="education_stage_id" id="education_stage_id" class="form-select" required>
                                <option value="">اختر المرحلة</option>
                                @foreach ($stages as $stage)
                                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>الصف الدراسي</label>
                            <select name="grade_id" id="grade_id" class="form-select" required>
                                <option value="">اختر الصف</option>
                            </select>
                        </div>
                    </div>

                    <!-- كلمة المرور -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">إضافة الطالب</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('#education_stage_id').on('change', function () {
        var stageId = $(this).val();
        $('#grade_id').html('<option value="">تحميل...</option>');

        if (stageId) {
            $.ajax({
                url: '/student/grades/by-stage/' + stageId,
                method: 'GET',
                success: function (response) {
                    var options = '<option value="">اختر الصف</option>';
                    response.grades.forEach(function (grade) {
                        options += `<option value="${grade.id}">${grade.name}</option>`;
                    });
                    $('#grade_id').html(options);
                },
                error: function () {
                    $('#grade_id').html('<option value="">حدث خطأ في التحميل</option>');
                }
            });
        } else {
            $('#grade_id').html('<option value="">اختر الصف</option>');
        }
    });
</script>
@endpush
