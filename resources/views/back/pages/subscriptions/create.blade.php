@extends('back.inc.master')

@section('body')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الاشتراكات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">قائمة الاشتراكات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة اشتراك</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if ($errors->has('custom_error'))
            <div class="alert alert-danger">
                {{ $errors->first('custom_error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body p-4">
                <form id="subscription-form" action="{{ route('subscriptions.store') }}" method="POST">
                    @csrf
                    <div id="subscription-rows">
                        <div class="row subscription-row mb-4 border rounded p-3">
                            <div class="col-md-4 mb-3">
                                <label>اختر الطالب</label>
                                <select name="subscriptions[0][student_id]" class="form-select student-select" required>
                                    <option value="">اختر الطالب</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->first_name }}
                                            {{ $student->last_name }} - {{ $student->phone }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>اختر الكورسات</label>
                                <select name="subscriptions[0][course_ids][]" class="form-select course-select" multiple
                                    required>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-flex flex-column justify-content-end gap-2">
                                <button type="button" class="btn btn-success add-row">+</button>
                                <button type="button" class="btn btn-danger remove-row"><i
                                        class='bx bx-trash'></i></button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">حفظ الاشتراكات</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let rowCount = 1;

        function initializeSelect2() {
            $('.student-select').select2({
                width: '100%',
                placeholder: "اختر الطالب",
                allowClear: true
            });

            $('.course-select').select2({
                width: '100%',
                placeholder: "اختر الكورسات",
                closeOnSelect: false,
                allowClear: true
            });
        }

        $(document).ready(function() {
            initializeSelect2();
        });

        $(document).on('click', '.add-row', function() {
            let row = $('.subscription-row').first().clone();

            row.html(row.html().replaceAll(/\[0\]/g, `[${rowCount}]`));

            row.find('.student-select').next('.select2').remove();
            row.find('.course-select').next('.select2').remove();

            row.find('.student-select').removeAttr('data-select2-id').removeClass('select2-hidden-accessible').val(
                null);
            row.find('.course-select').removeAttr('data-select2-id').removeClass('select2-hidden-accessible').val(
                null);

            $('#subscription-rows').append(row);
            initializeSelect2();

            rowCount++;
        });


        $(document).on('click', '.remove-row', function() {
            if ($('.subscription-row').length > 1) {
                $(this).closest('.subscription-row').remove();
            } else {
                alert('يجب أن يبقى صف واحد على الأقل');
            }
        });

        $('#subscription-form').submit(function(e) {
            let isValid = true;

            $('.subscription-row').each(function() {
                const student = $(this).find('.student-select').val();
                const courses = $(this).find('.course-select').val();

                if (!student || !courses || courses.length === 0) {
                    isValid = false;
                    $(this).css('border', '2px solid red');
                } else {
                    $(this).css('border', '');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('يرجى اختيار الطالب وكورس واحد على الأقل في كل صف.');
            }
        });
    </script>
@endpush
