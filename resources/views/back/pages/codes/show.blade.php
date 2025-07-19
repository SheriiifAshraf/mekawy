@extends('back.inc.master')

@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">تفاصيل الكود</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('codes.index') }}">الأكواد</a></li>
                        <li class="breadcrumb-item active" aria-current="page">تفاصيل الكود</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-primary mb-4">معلومات الكود</h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">الكود</label>
                                <input type="text" class="form-control" value="{{ $code->code }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">نوع الكود</label>
                                <input type="text" class="form-control" value="{{ ucfirst($code->type) }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- <div class="col-md-6">
                                <label class="form-label">الطالب</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->student->first_name . ' ' . $code->student->last_name }}" readonly>
                            </div> --}}
                            <div class="col-md-6">
                                <label class="form-label">مدة صلاحية الكود (بالأيام)</label>
                                <input type="text" class="form-control" value="{{ $code->period ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الحالة</label>
                                <input type="text" class="form-control" value="{{ ucfirst($code->status) }}" readonly>
                            </div>
                        </div>

                        <!-- Associated Entities -->
                        <h5 class="mt-4 text-secondary">المعلومات المرتبطة</h5>
                        <div class="row mb-3">
                            @if ($code->type == 'course')
                                <div class="col-md-6">
                                    <label class="form-label">اسم الكورس</label>
                                    <input type="text" class="form-control"
                                        value="{{ $code->course ? $code->course->name : 'غير مرتبط' }}" readonly>
                                </div>
                            @elseif ($code->type == 'lesson')
                                <div class="col-md-12">
                                    <label class="form-label">الدرس</label>
                                    <input type="text" class="form-control"
                                        value="{{ $code->lesson ? $code->lesson->name : 'غير مرتبط' }}" readonly>
                                </div>
                            @elseif ($code->type == 'video')
                                <div class="col-md-12">
                                    <label class="form-label">الفيديو</label>
                                    <input type="text" class="form-control"
                                        value="{{ $code->video ? $code->video->name : 'غير مرتبط' }}" readonly>
                                </div>
                            @endif
                        </div>

                        <!-- Timestamps -->
                        {{-- <h5 class="mt-4 text-secondary">التواريخ الزمنية</h5> --}}
                        <div class="row mb-3">
                            {{-- <div class="col-md-6">
                                <label class="form-label">تاريخ انتهاء الصلاحية</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->expires_at ? ($code->expires_at instanceof \Carbon\Carbon ? $code->expires_at->format('Y-m-d H:i:s') : $code->expires_at) : 'غير متوفر' }}"
                                    readonly>
                            </div> --}}
                            <div class="col-md-6">
                                <label class="form-label">تاريخ الاستخدام</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->used_at ? ($code->used_at instanceof \Carbon\Carbon ? $code->used_at->format('Y-m-d H:i:s') : $code->used_at) : 'غير متوفر' }}"
                                    readonly>
                            </div>
                        </div>

                        <!-- Student Information -->
                        <h5 class="mt-4 text-secondary">معلومات الطالب</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">اسم الطالب</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->student ? $code->student->first_name . ' ' . $code->student->last_name : 'غير مستخدم بعد' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم هاتف الطالب</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->student ? $code->student->phone : 'غير متوفر' }}" readonly>
                            </div>
                        </div>


                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">تاريخ الإلغاء</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->canceled_at ? ($code->canceled_at instanceof \Carbon\Carbon ? $code->canceled_at->format('Y-m-d H:i:s') : $code->canceled_at) : 'غير متوفر' }}"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاريخ البدء</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->started_at ? ($code->started_at instanceof \Carbon\Carbon ? $code->started_at->format('Y-m-d H:i:s') : $code->started_at) : 'غير متوفر' }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">تاريخ الانتهاء</label>
                                <input type="text" class="form-control"
                                    value="{{ $code->finished_at ? ($code->finished_at instanceof \Carbon\Carbon ? $code->finished_at->format('Y-m-d H:i:s') : $code->finished_at) : 'غير متوفر' }}"
                                    readonly>
                            </div>
                        </div>


                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('codes.index') }}" class="btn btn-secondary">العودة إلى الأكواد</a>
                            <a href="{{ route('codes.delete', $code->id) }}" class="btn btn-danger"
                                onclick="return confirm('هل أنت متأكد من حذف هذا الكود؟')">حذف الكود</a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
