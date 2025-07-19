<!--start header -->
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu">
                <i class='bx bx-menu'></i>
            </div>


            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal"
                        data-bs-target="#SearchModal">
                        {{--                        <a class="nav-link" href="avascript:;"> --}}
                        {{--                            <i class='bx bx-search'></i> --}}
                        {{--                        </a> --}}
                    </li>


                    <li class="nav-item dropdown dropdown-app">
                        {{--                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a> --}}
                        <div class="dropdown-menu dropdown-menu-end p-0">
                            <div class="app-container p-2 my-2">
                                <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">

                                </div><!--end row-->

                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown dropdown-large">
                        {{--                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">7</span> --}}
                        {{--                            <i class='bx bx-bell'></i> --}}
                        {{--                        </a> --}}
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-notifications-list">
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-large">
                        {{--                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span> --}}
                        {{--                            <i class='bx bx-shopping-bag'></i> --}}
                        {{--                        </a> --}}
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-message-list">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{-- <img src="{{ url('back/assets/images/logo.png') }}" class="user-img" alt="user avatar"> --}}
                    <div class="user-info">
                        {{-- <p class="user-name mb-0">{{ auth()->user()->name }}</p> --}}
                        <p class="user-name mb-0">تسجيل الخروج</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button style="font-size: 15px" type="submit" class="dropdown-item">
                                {{ __('تسجيل الخروج') }}
                                <i class="lni lni-power-switch text-danger"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<!--end header -->
