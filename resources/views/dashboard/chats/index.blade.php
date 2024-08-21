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
                                    <span
                                        class="badge badge-light-success">{{ $receivedMassages->where('is_read', 0)->count() }}</span>
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
            <!--begin::Card-->
            <div class="card">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Actions-->
                    <div class="d-flex flex-wrap gap-2">
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-sm form-check-custom form-check-solid me-4 me-lg-7">
                            <h3>{{ __('Massages') }}</h3>

                        </div>
                        <!--end::Checkbox-->

                    </div>
                    <!--end::Actions-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center flex-wrap gap-2">

                        <!--begin::Toggle-->
                        <a href="#"
                            class="btn btn-sm btn-icon btn-color-primary btn-light btn-active-light-primary d-lg-none"
                            data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top"
                            title="Toggle inbox menu" id="kt_inbox_aside_toggle">
                            <i class="ki-outline ki-burger-menu-2 fs-3 m-0"></i>
                        </a>
                        <!--end::Toggle-->
                    </div>
                    <!--end::Actions-->
                </div>
                <div class="card-body p-0">
                    <!--begin::Table-->
                    <table class="table table-hover table-row-dashed fs-6 gy-5 my-0">
                        <thead class="  text-white" style="background-color: rgb(0, 74, 111) !important">
                            <tr>
                                <th class="ps-3">{{ __('#') }}</th>
                                <th class="ps-3">{{ __('Sender') }}</th>

                                <th class="ps-3">{{ __('Title') }}</th>

                                <th class="ps-3">{{ __('Date') }}</th>
                                <th class="ps-3"></th>
                                {{-- <th class="ps-3">{{ __('actions') }}</th> --}}


                            </tr>
                        </thead>
                        <tbody>



                            @foreach ($receivedMassages as $massage)
                                @php
                                    $is_read_class = $massage->is_read == 1 ? '' : 'unread-message';
                                 @endphp
                                <tr class={{ $is_read_class }}>

                                     <td class="ps-3"> <!-- Apply the same padding class here -->
                                      -
                                    </td>

                                    <td class="w-150px w-md-175px">
                                        <div class="d-flex align-items-center text-gray-900">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px me-3">
                                                <span class="symbol-label"
                                                    style="background-image: url('{{ getImagePathFromDirectory($massage->sender->image, 'Employees') }}')"></span>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <span class="fw-semibold">{{ $massage->sender->name }}</span>
                                            <!--end::Name-->
                                        </div>
                                    </td>
                                    <td class="ps-3"> <!-- Apply the same padding class here -->
                                        <div class="text-gray-900 gap-1 pt-2">
                                            <!--begin::Heading-->
                                            <div href="#" class="text-gray-900">
                                                <span class="fw-bold">{{ $massage->subject }}</span>
                                                <span class="fw-bold d-none d-md-inine">-</span>
                                            </div>
                                            <!--end::Heading-->
                                        </div>
                                    </td>

                                    <td> <!-- Apply the same padding class here -->
                                        <span
                                            class="fw-semibold text-muted">{{ $massage->created_at->format('M d') }}</span>
                                    </td>
                                    <td class=" ps-3"> <!-- New column for actions -->
                                        <a href="{{ route('dashboard.chats.show-message', ['id' => $massage->id]) }}
											"
                                            class="btn btn-sm text-success">{{ __('Show Massage') }}</a>
                                    </td>


                                </tr>
                                </a>
                            @endforeach

                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card-->
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

@push('styles')
    <style>
        /* Define animation keyframes */
        @keyframes zoomIn {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.1);
                /* You can adjust the scale factor as needed */
            }
        }

        /* Apply animation to unread messages */
        .unread-message {
            font-weight: bold !important;
            animation: zoomIn 0.5s ease-in-out;
            /* Adjust duration and timing function as needed */
        }
    </style>
@endpush
