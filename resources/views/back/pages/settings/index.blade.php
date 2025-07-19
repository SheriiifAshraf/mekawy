@extends('back.inc.master')

@section('body')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">الإعدادات</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">جميع الإعدادات</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

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
        <div class="card-header">
            <h3>الإعدادات</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>القيمة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settings as $setting)
                                <tr>
                                    <td>{{ $setting->key }}</td>
                                    <td>
                                        @if ($setting->key == 'image')
                                            <input type="file" name="image" class="form-control">
                                            @if ($setting->getFirstMediaUrl('image'))
                                                <div class="mt-3">
                                                    <img style="width: 200px; height: 200px; object-fit: cover;"
                                                        src="{{ asset('storage/' . $setting->getFirstMedia('image')->getAttribute('id') . '/' . $setting->getFirstMedia('image')->file_name) }}"
                                                        alt="Current Image" style="max-width: 100%; height: auto;">
                                                </div>
                                            @endif
                                        @else
                                            <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">تحديث الإعدادات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
