@extends('partials.dashboard.master')
@push('styles')
    <link href="{{ asset('dashboard-assets/css/datatables' . (isDarkMode() ? '.dark' : '') . '.bundle.css') }}"
        rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <!-- begin :: Subheader -->
    <div class="toolbar">

        <div class="container-fluid d-flex flex-stack">

            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

                <!-- begin :: Title -->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ __('Chats') }}</h1>
                <!-- end   :: Title -->

                <!-- begin :: Separator -->
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!-- end   :: Separator -->

                <!-- begin :: Breadcrumb -->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!-- begin :: Item -->
                    <li class="breadcrumb-item text-muted">
                        {{ __('Chats list') }}
                    </li>
                    <!-- end   :: Item -->
                </ul>
                <!-- end   :: Breadcrumb -->

            </div>

        </div>

    </div>
    <!-- end   :: Subheader -->

    <!--begin::Inbox App - Messages -->
    <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px" data-kt-drawer="true"
            data-kt-drawer-name="inbox-aside" data-kt-drawer-activate="{default: true, lg: false}"
            data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_inbox_aside_toggle">
            <!--begin::Sticky aside-->
            <div class="card card-flush mb-0" data-kt-sticky="false" data-kt-sticky-name="inbox-aside-sticky"
                data-kt-sticky-offset="{default: false, xl: '100px'}" data-kt-sticky-width="{lg: '275px'}"
                data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false"
                data-kt-sticky-zindex="95">
                <!--begin::Aside content-->
                <div class="card-body">
                    <!--begin::Button-->
                    <a href="{{ route('dashboard.chats.create') }}" class="btn   fw-bold w-100 mb-8"
                        style="background-color:rgb(0, 74, 111) !important; color:white;">{{ __('New Message') }}</a>
                    <!--end::Button-->
                    <!--begin::Menu-->
                    <div
                        class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary mb-10">
                        <!--begin::Menu item-->
                        <a href="{{ route('dashboard.chats.index') }}">

                            <div class="menu-item mb-3">
                                <!--begin::Inbox-->
                                <span class="menu-link active">
                                    <span class="menu-icon">
                                        <i class="fas fa-envelope fs-2 me-3 " style="color:rgb(0, 74, 111);"></i>

                                    </span>
                                    <span
                                        class="menu-title
                                       fw-bold">{{ __('Inbox') }}</span>
                                    <span class="badge badge-light-success">{{ $receivedMassages->where('is_read',0)->count() }}</span>
                                </span>
                                <!--end::Inbox-->
                            </div>
                        </a>
                        <!--end::Menu item-->

                        <a href="{{ route('dashboard.chats.show', Auth::user()->id) }}">
                            <div class="menu-item mb-3">
                                <!--begin::Sent-->
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fas fa-paper-plane fs-2 me-3 " style="color:rgb(0, 74, 111);"></i>
                                    </span>
                                    <span class="menu-title fw-bold">{{ __('Sent') }}</span>

                                </span>
                                <!--end::Sent-->
                            </div>
                            <!--end::Menu item-->
                        </a>

                    </div>
                    <!--end::Menu-->

                </div>
                <!--end::Aside content-->
            </div>
            <!--end::Sticky aside-->
        </div>
        <!--end::Sidebar-->
        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <h2 class="card-title m-0">{{ __('Compose Message') }}</h2>
                    <!--begin::Toggle-->
                    <a href="#"
                        class="btn btn-sm btn-icon btn-color-primary btn-light btn-active-light-primary d-lg-none"
                        data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" id="kt_inbox_aside_toggle"
                        aria-label="Toggle inbox menu" data-bs-original-title="Toggle inbox menu" data-kt-initialized="1">
                        <i class="ki-outline ki-burger-menu-2 fs-3 m-0"></i>
                    </a>
                    <!--end::Toggle-->
                </div>
                <div class="card-body p-0">
                    <!--begin::Form-->

                    <!--begin::Body-->
                    <form action="{{ route('dashboard.chats.store') }}" class="form" method="post" id="submitted-form"
                        data-redirection-url="{{ route('dashboard.chats.index') }}" id="kt_inbox_compose_form">
                        @csrf
                        <div class="d-block">
                            <!--begin::To-->
                            <div class="d-flex align-items-center border-bottom px-8 min-h-50px">
                                <!--begin::Label-->
                                <div class="text-gray-900 fw-bold w-75px">{{ __('To') . ' ' . ':' }}</div>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select" data-control="select2" name="compose_to[]" multiple
                                    id="compose_to-sp" data-placeholder="{{ __('Choose the employees') }}"
                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach

                                </select>

                                <!--end::Input-->

                            </div>
                            <p class="invalid-feedback text-center" id="compose_to"></p>

                            <!--end::To-->


                            <!--begin::Subject-->
                            <div class="border-bottom">
                                <input class="form-control form-control-transparent border-0 px-8 min-h-45px"
                                    name="compose_subject" placeholder={{ __('Subject') }}>
                            </div>
                            <p class="invalid-feedback text-center" id="compose_subject"></p>


                            <div id="kt_inbox_form_editor"
                                class="bg-transparent border-0 h-350px px-3 ql-container ql-snow">
                                <textarea id="kt_inbox_form_editor" class="form-control border-0 h-350px px-3" name="compose_contain" rows="5"></textarea>

                            </div>
                            <p class="invalid-feedback text-center" id="compose_contain"></p>

                            <!--end::Message-->

                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top">
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center me-3">
                                <!--begin::Send-->
                                <div class="btn-group me-4">
                                    <!--begin::Submit-->
                                    <!-- begin :: Submit btn -->
                                    <button type="submit" class="btn btn-primary" id="submit-btn">

                                        <span class="indicator-label">{{ __('Send') }}</span>

                                        <!-- begin :: Indicator -->
                                        <span class="indicator-progress">{{ __('Please wait ...') }}
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                        <!-- end   :: Indicator -->

                                    </button>
                                    <!-- end   :: Submit btn -->
                                    <!--end::Submit-->

                                </div>
                                <!--end::Send-->

                            </div>
                            <!--end::Actions-->

                        </div>
                        <!--end::Footer-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Inbox App - Messages -->
@endsection
@push('scripts')
    <script src="{{ asset('js/dashboard/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/dashboard/datatables/delegates.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/apps/inbox/listing.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/utilities/modals/users-search.js') }}"></script>

    <!-- <script>
        let currentUserId = {{ auth()->id() }};
    </script> -->
@endpush
