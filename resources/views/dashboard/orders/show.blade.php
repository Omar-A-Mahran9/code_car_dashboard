@extends('partials.dashboard.master')
@section('content')
    <!-- begin :: Subheader -->
    <div class="toolbar">

        <div class="container-fluid d-flex flex-stack">

            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

                <!-- begin :: Title -->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"><a
                        href="{{ route('dashboard.orders.index') }}"
                        class="text-muted text-hover-primary">{{ __('Orders') }}</a></h1>
                <!-- end   :: Title -->

                <!-- begin :: Separator -->
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!-- end   :: Separator -->

                <!-- begin :: Breadcrumb -->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!-- begin :: Item -->
                    <li class="breadcrumb-item text-muted">
                        {{ __('Order data') }}
                    </li>
                    <!-- end   :: Item -->
                </ul>
                <!-- end   :: Breadcrumb -->

            </div>

        </div>

    </div>
    <!-- end   :: Subheader -->

    <!--begin::Order details page-->
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-lg-n2 me-auto">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                        href="#kt_ecommerce_sales_order_summary">{{ __('Order Summary') }}</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#kt_ecommerce_sales_order_history">{{ __('Order History') }}</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                @can('update_orders')
                    @if ($order && $order['orderDetailsCar']['cars'] == null)
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                href="#kt_ecommerceـfinal_approval">{{ __('Fianl Approval') }}</a>
                        </li>
                    @endif
                @endcan
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->


            <div class="w-200px">

                <!--begin::Select2-->
                <select class="form-select" data-control="select2" data-hide-search="false" id="order-status-sp"
                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}" data-placeholder="{{ __('Status') }}">
                    @foreach (settings()->getOrdersStatus() ?? [] as $status)
                        <option value="{{ $status['id'] . '_' . $status['name_en'] }}"
                            {{ $status['id'] == $order['status_id'] ? 'selected' : '' }}>
                            {{ $status['name_' . getLocale()] }}</option>
                    @endforeach
                </select>
                <!--end::Select2-->
            </div>
            @can('update_Distribution_of_Orders')
                <div class="w-200px">
                    <select class="form-select" data-control="select2" data-hide-search="false" name="employee_id"
                        id="employee-sp" data-placeholder="{{ __('Assign the employee') }}"
                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                        <option value="" selected disabled>{{ __('Assign the employee') }}</option>
                        @foreach ($employees as $employeedat)
                            <option value="{{ $employeedat->id }}" {{ $employee->id == $employeedat->id ? 'selected' : '' }}>
                                {{ $employeedat->name }} </option>
                        @endforeach
                    </select>
                    <p class="invalid-feedback" id="employee_id"></p>

                </div>
            @endcan
            {{-- @endif --}}


        </div>

        <!--begin::Tab content-->
        <div class="tab-content">

            <!--begin::Tab pane-->
            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                <!--begin::Order summary-->
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10 mb-5">
                    <!--begin::Order details-->

                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title d-flex justify-content-between w-100">
                                <div>
                                    <h2>{{ __('Order Details') }} ( #{{ $order['id'] }} )</h2>
                                </div>
                                @if ($order['edited'])
                                    <div>
                                        <span class="badge badge-warning me-3">{{ __('edited') }}</span>
                                    </div>
                                @endif
                            </div>


                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        @if ($order['orderDetailsCar']['bank_offer_id'])
                                            <tr>

                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-briefcase mx-2"></i>
                                                        </span> {{ __('offer name') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end"><a
                                                        href="/dashboard/bank-offers/{{ $order['orderDetailsCar']->bank_offer->id }}">
                                                        <h5 style="font-weight:bold;color:rgb(0, 74, 111)">
                                                            {{ $order['orderDetailsCar']->bank_offer->title }}</h5>
                                                    </a>
                                                </td>

                                            </tr>
                                        @endif
                                        @if ($order['orderDetailsCar']['sector_id'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-briefcase mx-2"></i>
                                                        </span> {{ __('The Sector') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">
                                                    {{ $order['orderDetailsCar']->sector->name }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order['city_id'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-map-marker mx-2"></i>
                                                        </span>
                                                        {{ __('City') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order->city->name }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order['nationality_id'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-briefcase mx-2"></i>
                                                        </span> {{ __('The nationality') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order->nationality->name }}
                                                </td>
                                            </tr>
                                        @endif
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <i class="fa fa-calendar mx-2"></i>
                                                    </span> {{ __('Date') }}
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ date('Y-m-d', strtotime($order['created_at'])) }}
                                            </td>
                                        </tr>
                                        <!--end::Date-->
                                        <!--begin::Time-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <i class="fa fa-clock mx-2"></i>
                                                    </span> {{ __('Time') }}
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ date('H:i a', strtotime($order['created_at'])) }}
                                            </td>
                                        </tr>
                                        <!--end::Time-->
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order details-->
                    <!--begin::Customer details-->
                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header d-flex justify-content-between align-items-between">
                            <div class="card-title ">
                                <h2 style="font-weight:bold; ">{{ __('Customer Details') }}</h2>

                            </div>

                            @if ($order['orderDetailsCar']['type'] == 'individual')
                                <div class="ps-4">
                                    <a href="https://wa.me/{{ $order['phone'] }}?text={{ urlencode(__('السلام عليكم. موقع كود كار للسيارات يرحب بكم ويسعدنا التواصل معك بخصوص طلبك رقم ' . $order['id'] . ' لسيارة: ' . $order['car_name'] . '')) }}"
                                        target="_blank" title="Chat on WhatsApp" class="whatsapp-icon">
                                        <img src="{{ asset('dashboard-assets/media/svg/social-logos/whatsapp.svg') }}"
                                            alt="WhatsApp Logo"
                                            style="width:50px; height: 50px; margin-left: 23px; margin-right: 23px;">
                                    </a>
                                </div>
                            @else
                                @if ($order['orderDetailsCar']['cars'])
                                    @php
                                        $carCount = 0;
                                    @endphp
                                    @foreach (json_decode($order['orderDetailsCar']['cars'], true) as $car)
                                        @if (isset($car))
                                            {{-- Here you can put any code related to displaying individual cars --}}
                                            @php
                                                $carCount += $car['count'];
                                            @endphp
                                        @endif <!--end::Cars-->
                                    @endforeach
                                    <div class="ps-4">
                                        <a href="https://wa.me/{{ $order['phone'] }}?text={{ urlencode(__('السلام عليكم. موقع كود كار للسيارات يرحب بكم ويسعدنا التواصل معك بخصوص طلبك رقم ' . $order['id'] . ' ' . ' العدد الطلوب' . ' ' . $carCount . ' ' . 'سيارة')) }}"
                                            target="_blank" title="Chat on WhatsApp" class="whatsapp-icon">
                                            <img src="{{ asset('dashboard-assets/media/svg/social-logos/whatsapp.svg') }}"
                                                alt="WhatsApp Logo"
                                                style="width:50px; height: 50px; margin-left: 23px; margin-right: 23px;">
                                        </a>
                                    </div>
                                @else
                                    <a href="https://wa.me/{{ $order['phone'] }}?text={{ urlencode(__('السلام عليكم. موقع كود كار للسيارات يرحب بكم ويسعدنا التواصل معك بخصوص طلبك رقم ' . $order['id'] . ' لسيارة: ' . ' ' . $order['car_name'] . ' ' . 'عدد' . ' ' . $order['orderDetailsCar']['car_count'])) }}"
                                        target="_blank" title="Chat on WhatsApp" class="whatsapp-icon">
                                        <img src="{{ asset('dashboard-assets/media/svg/social-logos/whatsapp.svg') }}"
                                            alt="WhatsApp Logo"
                                            style="width:50px; height: 50px; margin-left: 23px; margin-right: 23px;">
                                    </a>
                                @endif
                            @endif




                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        <!--begin::Customer name-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <i class="fa fa-user mx-2"></i>
                                                    </span>
                                                    {{ __('Customer') }}
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <!--begin::Name-->
                                                    {{ $order['name'] }}
                                                    <!--end::Name-->
                                                </div>
                                            </td>
                                        </tr>
                                        <!--end::Customer name-->
                                        <!--begin::Date-->
                                        @if ($order['phone'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Svg Icon-->
                                                        <span class="svg-icon svg-icon-2 me-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z"
                                                                    fill="black" />
                                                                <path opacity="0.3" d="M19 4H5V20H19V4Z"
                                                                    fill="black" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                        {{ __('Phone') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order['phone'] }}</td>
                                            </tr>
                                        @endif
                                        @if ($order['identity_no'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-user mx-2"></i>
                                                        </span>
                                                        {{ __('Identity no') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ __($order['identity_no']) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order['sex'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-user mx-2"></i>
                                                        </span>
                                                        {{ __('Sex') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ __($order['sex']) }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($order['email'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-map-marker mx-2"></i>
                                                        </span>
                                                        {{ __('Email') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order['email'] }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($order['birth_date'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-calendar mx-2"></i>
                                                        </span>


                                                        {{ __('Birth date') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order['birth_date'] }}</td>
                                            </tr>
                                        @endif
                                        <!--end::Date-->
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Customer details-->
                </div>
                <!--end::Order summary-->
                <!--begin::Orders-->

                @if ($order['type'] == 'car')
                    @if ($order['orderDetailsCar']['type'] == 'individual')
                        <div class="d-flex flex-column gap-7 gap-lg-10 mt-5">

                            <!--begin::Product List-->
                            <div class="card card-flush  flex-row-fluid overflow-hidden">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>{{ __('Order') }} #{{ $order['id'] . ' ' }} </h2>
                                    </div>
                                    <div class="card-title">
                                        <h2>{{ __('Order Type') . ' : ' }}
                                            {{ __(ucfirst($order['orderDetailsCar']['type'])) . ' ' }} </h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                            <!--begin::Table body-->
                                            <tbody class="fw-bold text-gray-600">
                                                <tr>
                                                    <td class="text-start fw-boldest" colspan="4">{{ __('Car') }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end align-items-center">
                                                            @if ($order->car)
                                                                <!--begin::Thumbnail-->
                                                                <a href="/dashboard/cars/{{ $order['car_id'] }}"
                                                                    class="symbol symbol-50px">
                                                                    <img src="{{ getImagePathFromDirectory($order->car->main_image, 'Cars') }}"
                                                                        alt="Car Image" class="symbol-label">
                                                                </a>
                                                                <div class="ms-5">
                                                                    <a href="/dashboard/cars/{{ $order['car_id'] }}"
                                                                        target="_blank"
                                                                        class="fw-boldest text-gray-600 text-hover-primary">{{ $order['car_name'] }}</a>
                                                                </div>
                                                            @else
                                                                <div class="ms-5">
                                                                    <a href="#"
                                                                        class="fw-boldest text-gray-600 text-hover-primary">{{ $order['car_name'] }}</a>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-boldest">{{ __('Price') }}</td>
                                                    <td class="text-end fw-boldest" colspan="4">
                                                        {{ $order['price'] . ' ' . currency() }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-boldest">{{ __('Payment Type') }}</td>
                                                    <td class="text-end fw-boldest" colspan="4">
                                                        {{ __(ucfirst($order['orderDetailsCar']['payment_type'])) }}
                                                    </td>
                                                </tr>
                                                @if ($order['orderDetailsCar']['payment_type'] == 'finance')
                                                    @if ($order['orderDetailsCar']['work'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('Work') }}</td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['work'] }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Salary') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['salary'] . ' ' . currency() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Commitments') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['commitments'] . ' ' . currency() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Is there a mortgage loan') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['having_loan'] ? __('Yes') : __('No') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">
                                                            {{ __('Is there a supported mortgage loan') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['having_loan_support'] ? __('Yes') : __('No') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('mortgage loan price') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['having_loan_support_price'] }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Total Price for offer') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ optional($offerSelected->sectors->first())->pivot->support ?? 'N/A' }}
                                                            %
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Find price for offer') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ optional($offerSelected->sectors->first())->pivot->support> ? __('Yes') : __('No') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest" style="font-weight:900">
                                                            {{ __('Max limit to monthely installment') . ' ' }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $approve_amount . ' ' . currency() }}
                                                        </td>
                                                    </tr>
                                                    @if ($order['orderDetailsCar']['driving_license'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('Driving License Status') }}</td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ __(str_replace('_', ' ', $order['orderDetailsCar']['driving_license'])) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($order['orderDetailsCar']['traffic_violations'] == 0 || 1)
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('traffic violations') }}</td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['traffic_violations'] ? __('Yes') : __('No') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Installment Time') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['installment'] . ' ' . __('years') }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="fw-boldest">{{ __('The first installment') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['first_installment'] }} %
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('The first installment amount') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['first_payment_value'] . ' ' . currency() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('The last installment') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['last_installment'] }} %
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('The last installment amount') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['last_payment_value'] . ' ' . currency() }}
                                                        </td>
                                                    </tr>
                                                    @if ($order['orderDetailsCar']['Monthly_installment'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('The installment amount') }}
                                                            </td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['Monthly_installment'] . ' ' . currency() }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if ($order['orderDetailsCar']['Adminstrative_fees'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('adminstrative fees') }}
                                                            </td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['Adminstrative_fees'] . ' ' . currency() }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($order['orderDetailsCar']['finance_amount'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('Finance amounts') }}
                                                            </td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['finance_amount'] . ' ' . currency() }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if ($order['orderDetailsCar']['bank'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('Bank') }}</td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['bank']['name'] }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            </tbody>
                                            <!--end::Table head-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Product List-->
                        </div>
                    @else
                        <div class="d-flex flex-column gap-7 gap-lg-10 mt-5">

                            <!--begin::Product List-->
                            <div class="card card-flush  flex-row-fluid overflow-hidden">

                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>{{ __('Order') }} #{{ $order['id'] . ' ' }} </h2>
                                    </div>
                                    <div class="card-title">
                                        <h2>{{ __('Order Type') . ' : ' }}
                                            {{ __(ucfirst($order['orderDetailsCar']['type'])) . ' ' }} </h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">

                                            <!--begin::Table body-->
                                            <tbody class="fw-bold text-gray-600">
                                                <!--begin::Cars-->
                                                @if ($order['orderDetailsCar']['cars'])
                                                    <!--begin::Table head-->
                                                    <thead>
                                                        <tr
                                                            class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                            <th class="min-w-175px fw-boldest">{{ __('Car') }}
                                                            </th>
                                                            <th class="min-w-70px text-end fw-boldest" colspan="4">
                                                                {{ __('Quantity') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <!--end::Table head-->
                                                    @foreach (json_decode($order['orderDetailsCar']['cars'], true) as $car)
                                                        @if (isset($car))
                                                            <tr class="p-5">
                                                                <td class="text-start fw-boldest">
                                                                    {{ $car['car_name'] }}
                                                                </td>
                                                                <td class="text-end fw-boldest" colspan="4">
                                                                    {{ $car['count'] . ' ' . __('car') }}</td>
                                                            </tr>
                                                        @endif <!--end::Cars-->
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-start fw-boldest" colspan="4">
                                                            {{ __('Car') }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-end align-items-center">
                                                                @if ($order->car)
                                                                    <!--begin::Thumbnail-->
                                                                    <a href="/dashboard/cars/{{ $order['car_id'] }}"
                                                                        class="symbol symbol-50px">
                                                                        <img src="{{ getImagePathFromDirectory($order->car->main_image, 'Cars') }}"
                                                                            alt="Car Image" class="symbol-label">
                                                                    </a>
                                                                    <div class="ms-5">
                                                                        <a href="/dashboard/cars/{{ $order['car_id'] }}"
                                                                            target="_blank"
                                                                            class="fw-boldest text-gray-600 text-hover-primary">{{ $order['car_name'] }}</a>
                                                                    </div>
                                                                @else
                                                                    <div class="ms-5">
                                                                        <a href="#"
                                                                            class="fw-boldest text-gray-600 text-hover-primary">{{ $order['car_name'] }}</a>
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if (!$order['orderDetailsCar']['cars'])
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Price') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order->car->getPriceAfterVatAttribute() . ' ' . currency() }}
                                                        </td>
                                                    </tr>
                                                    @if ($order['orderDetailsCar']['car_count'])
                                                        <tr>
                                                            <td class="fw-boldest">{{ __('Total Price') }}</td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['price'] . ' ' . currency() }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                <tr>
                                                    <td class="fw-boldest">{{ __('Payment Type') }}</td>
                                                    <td class="text-end fw-boldest" colspan="4">
                                                        {{ __(ucfirst($order['orderDetailsCar']['payment_type'])) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-boldest">{{ __('Organization Name') }}</td>
                                                    <td class="text-end fw-boldest" colspan="4">
                                                        {{ $order['orderDetailsCar']['organization_name'] }}</td>
                                                </tr>
                                                @if ($order['orderDetailsCar']['organization_email'])
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Organization Email') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['organization_email'] }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order['orderDetailsCar']['commercial_registration_no'])
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('commercial registration no') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['commercial_registration_no'] }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($organization_activity)
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Organization Activity') }}
                                                        </td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $organization_activity->title }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($organization_type->title)
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Organization Type') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $organization_type->title }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="fw-boldest">{{ __('Organization Age') }}</td>
                                                    <td class="text-end fw-boldest" colspan="4">
                                                        {{ $order['orderDetailsCar']['organization_age'] . ' ' . __('Years') }}
                                                    </td>
                                                </tr>
                                                @if ($order['orderDetailsCar']['car_count'])
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Cars Count') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['car_count'] . ' ' . __('Cars') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($order['orderDetailsCar']['bank'])
                                                    <tr>
                                                        <td class="fw-boldest">{{ __('Bank') }}</td>
                                                        <td class="text-end fw-boldest" colspan="4">
                                                            {{ $order['orderDetailsCar']['bank']['name'] }}</td>
                                                    </tr>
                                                @endif
                                                @if ($order['orderDetailsCar']['payment_type'] == 'finance')
                                                    @if ($order['orderDetailsCar']['organization_location'])
                                                        <tr>
                                                            <td class="fw-boldest">
                                                                {{ __("The company's headquarter") }}
                                                            </td>
                                                            <td class="text-end fw-boldest" colspan="4">
                                                                {{ $order['orderDetailsCar']['organization_location'] }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            </tbody>
                                            <!--end::Table head-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Product List-->
                        </div>
                    @endif
                @else
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::Product List-->
                        <div class="card card-flush flex-row-fluid overflow-hidden">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>{{ __('Order') }} #{{ $order['id'] . ' ' }} </h2>
                                </div>
                                <div class="card-title">
                                    <h2>{{ __('Order Type') . ' : ' }}
                                        {{ __(str_replace('_', ' ', $order['type'])) . ' ' }} </h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600">
                                            <tr>
                                                <td class="text-start fw-boldest" colspan="4">{{ __('Car name') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        <!--be  gin::Title-->
                                                        <div class="ms-5">
                                                            <a href="#"
                                                                class="fw-boldest text-gray-600 text-hover-primary">{{ $order['car_name'] }}</a>
                                                        </div>
                                                        <!--end::Title-->
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-boldest">{{ __('City') }}</td>
                                                <td class="text-end fw-boldest" colspan="4">
                                                    {{ $order['city']['name'] }}</td>
                                            </tr>
                                        </tbody>
                                        <!--end::Table head-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Product List-->
                    </div>


                @endif
                <!--end::Orders-->
                <!-- Assuming you are using Bootstrap that comes with Metronic -->
                @if ($order['identity_Card'] || $order['License_Card'] || $order['Hr_Letter_Image'] || $order['Insurance_Image'])
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div style="text-align: center; margin-bottom:30px">
                                    <h5 class="card-title">{{ __('Download Documents') }}</h5>
                                    <p class="card-text">
                                        {{ __('Click on the buttons below to download your documents') }}.</p>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    @if ($order['identity_Card'])
                                        <a href="{{ route('dashboard.files.download', $order['identity_Card']) }}"
                                            class="btn btn-success me-md-2" target="_blank">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                            {{ __('Download Identity Card') }}
                                        </a>
                                    @endif
                                    @if ($order['License_Card'])
                                        <a href="{{ route('dashboard.files.download', $order['License_Card']) }}"
                                            class="btn btn-info me-md-2" target="_blank">
                                            <i
                                                class="bi bi-file-earmark-arrow-down"></i>{{ __('Download Identity License Card') }}
                                        </a>
                                    @endif
                                    @if ($order['Hr_Letter_Image'])
                                        <a href="{{ route('dashboard.files.download', $order['Hr_Letter_Image']) }}"
                                            class="btn btn-warning me-md-2" target="_blank">
                                            <i
                                                class="bi bi-file-earmark-arrow-down"></i>{{ __('Download License Hr Letter Image') }}
                                        </a>
                                    @endif
                                    @if ($order['Insurance_Image'])
                                        <a href="{{ route('dashboard.files.download', $order['Insurance_Image']) }}"
                                            class="btn btn-primary me-md-2" target="_blank">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                            {{ __('Download Insurance Image') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                <!--begin::Order summary-->
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10 mb-5">
                    <!--begin::Order details-->

                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('Order Details') }} ( #{{ $order['id'] }} )</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        @if ($order['orderDetailsCar']['bank_offer_id'])
                                            <tr>

                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-briefcase mx-2"></i>
                                                        </span> {{ __('offer name') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end"><a
                                                        href="/dashboard/bank-offers/{{ $order['orderDetailsCar']->bank_offer->id }}">
                                                        <h5 style="font-weight:bold;color:rgb(0, 74, 111)">
                                                            {{ $order['orderDetailsCar']->bank_offer->title }}</h5>
                                                    </a>
                                                </td>

                                            </tr>
                                        @endif
                                        @if ($order['orderDetailsCar']['sector_id'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-briefcase mx-2"></i>
                                                        </span> {{ __('The Sector') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">
                                                    {{ $order['orderDetailsCar']->sector->name }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order['city_id'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-map-marker mx-2"></i>
                                                        </span>
                                                        {{ __('City') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order->city->name }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order['nationality_id'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-briefcase mx-2"></i>
                                                        </span> {{ __('The nationality') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order->nationality->name }}
                                                </td>
                                            </tr>
                                        @endif
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <i class="fa fa-calendar mx-2"></i>
                                                    </span> {{ __('Date') }}
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ date('Y-m-d', strtotime($order['created_at'])) }}
                                            </td>
                                        </tr>
                                        <!--end::Date-->
                                        <!--begin::Time-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <i class="fa fa-clock mx-2"></i>
                                                    </span> {{ __('Time') }}
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ date('H:i a', strtotime($order['created_at'])) }}
                                            </td>
                                        </tr>
                                        <!--end::Time-->
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order details-->
                    <!--begin::Customer details-->
                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header d-flex justify-content-between align-items-between">
                            <div class="card-title ">
                                <h2 style="font-weight:bold; ">{{ __('Customer Details') }}</h2>

                            </div>

                            @if ($order['orderDetailsCar']['type'] == 'individual')
                                <div class="ps-4">
                                    <a href="https://wa.me/{{ $order['phone'] }}?text={{ urlencode(__('السلام عليكم. موقع كود كار للسيارات يرحب بكم ويسعدنا التواصل معك بخصوص طلبك رقم ' . $order['id'] . ' لسيارة: ' . $order['car_name'] . '')) }}"
                                        target="_blank" title="Chat on WhatsApp" class="whatsapp-icon">
                                        <img src="{{ asset('dashboard-assets/media/svg/social-logos/whatsapp.svg') }}"
                                            alt="WhatsApp Logo"
                                            style="width:50px; height: 50px; margin-left: 23px; margin-right: 23px;">
                                    </a>
                                </div>
                            @else
                                @if ($order['orderDetailsCar']['cars'])
                                    @php
                                        $carCount = 0;
                                    @endphp
                                    @foreach (json_decode($order['orderDetailsCar']['cars'], true) as $car)
                                        @if (isset($car))
                                            {{-- Here you can put any code related to displaying individual cars --}}
                                            @php
                                                $carCount += $car['count'];
                                            @endphp
                                        @endif <!--end::Cars-->
                                    @endforeach
                                    <div class="ps-4">
                                        <a href="https://wa.me/{{ $order['phone'] }}?text={{ urlencode(__('السلام عليكم. موقع كود كار للسيارات يرحب بكم ويسعدنا التواصل معك بخصوص طلبك رقم ' . $order['id'] . ' ' . ' العدد الطلوب' . ' ' . $carCount . ' ' . 'سيارة')) }}"
                                            target="_blank" title="Chat on WhatsApp" class="whatsapp-icon">
                                            <img src="{{ asset('dashboard-assets/media/svg/social-logos/whatsapp.svg') }}"
                                                alt="WhatsApp Logo"
                                                style="width:50px; height: 50px; margin-left: 23px; margin-right: 23px;">
                                        </a>
                                    </div>
                                @else
                                    <a href="https://wa.me/{{ $order['phone'] }}?text={{ urlencode(__('السلام عليكم. موقع كود كار للسيارات يرحب بكم ويسعدنا التواصل معك بخصوص طلبك رقم ' . $order['id'] . ' لسيارة: ' . ' ' . $order['car_name'] . ' ' . 'عدد' . ' ' . $order['orderDetailsCar']['car_count'])) }}"
                                        target="_blank" title="Chat on WhatsApp" class="whatsapp-icon">
                                        <img src="{{ asset('dashboard-assets/media/svg/social-logos/whatsapp.svg') }}"
                                            alt="WhatsApp Logo"
                                            style="width:50px; height: 50px; margin-left: 23px; margin-right: 23px;">
                                    </a>
                                @endif
                            @endif




                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        <!--begin::Customer name-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <i class="fa fa-user mx-2"></i>
                                                    </span>
                                                    {{ __('Customer') }}
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <!--begin::Name-->
                                                    {{ $order['name'] }}
                                                    <!--end::Name-->
                                                </div>
                                            </td>
                                        </tr>
                                        <!--end::Customer name-->
                                        <!--begin::Date-->
                                        @if ($order['phone'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Svg Icon-->
                                                        <span class="svg-icon svg-icon-2 me-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z"
                                                                    fill="black" />
                                                                <path opacity="0.3" d="M19 4H5V20H19V4Z"
                                                                    fill="black" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                        {{ __('Phone') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order['phone'] }}</td>
                                            </tr>
                                        @endif
                                        @if ($order['identity_no'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-user mx-2"></i>
                                                        </span>
                                                        {{ __('Identity no') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ __($order['identity_no']) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order['sex'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-user mx-2"></i>
                                                        </span>
                                                        {{ __('Sex') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ __($order['sex']) }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($order['email'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-map-marker mx-2"></i>
                                                        </span>
                                                        {{ __('Email') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order['email'] }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($order['birth_date'])
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            <i class="fa fa-calendar mx-2"></i>
                                                        </span>


                                                        {{ __('Birth date') }}
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">{{ $order['birth_date'] }}</td>
                                            </tr>
                                        @endif
                                        <!--end::Date-->
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Customer details-->
                </div>
                <!--end::Order summary-->
                <!--begin::Orders-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Order history-->
                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('Order History') }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle text-center table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="text-center text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-70px">{{ __('Order Status') }}</th>
                                            <th class="min-w-175px">{{ __('Comment') }}</th>
                                            <th class="min-w-100px">{{ __('employee') }}</th>

                                            <th class="min-w-100px">{{ __('assign to') }}</th>



                                            <th class="min-w-100px">{{ __('edited by') }}</th>



                                            <th class="min-w-100px">{{ __('Date') }}</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">

                                        @foreach ($order->statusHistory as $record)
                                            @php($statusObj = getStatusObject($record['status']))

                                            <tr>

                                                <td>
                                                    <div class="badge"
                                                        style="background-color:{{ $statusObj['color'] }}">
                                                        {{ $statusObj['name_' . getLocale()] }}</div>
                                                </td>

                                                <td>{{ $record['comment'] ?? '-' }}</td>
                                                <td>{{ $record['employee']['name'] }}</td>

                                                <td>{{ $record['assign']['name'] ?? '-' }}</td>

                                                <td>{{ $record['edited']['name'] ?? '-' }}</td>

                                                <td>
                                                    {{ date('Y-m-d', strtotime($record['created_at'])) . ' / ' . date('H:i a', strtotime($record['created_at'])) }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order history-->
                </div>
                <!--end::Orders-->
            </div>
            <!--end::Tab pane-->

            <div class="tab-pane fade" id="kt_ecommerceـfinal_approval" role="tab-panel">


                <!--begin::Orders-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Order history-->
                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('Fianl Approval') }}
                                </h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        @if ($order['orderDetailsCar']['type'] == 'individual' && $order['orderDetailsCar']['payment_type'] == 'finance')
                            <div class="card-body pt-0">
                                <div class="row justify-content-center">
                                    <div class="col-xl-12">

                                        <form action="{{ route('dashboard.orders.final_approval') }}" class="form"
                                            method="post" id="submitted-form"
                                            data-redirection-url="{{ route('dashboard.orders.index') }}">

                                            <input type="hidden" name="old_order" value="{{ $order }}">

                                            <!--end::Form-->

                                            <div class="pb-8">
                                                @if ($order->car)
                                                    <div>
                                                        <h3 class="text-center mb-4" style="font-weight: 900">
                                                            {{ __('Car Data') }}</h3>
                                                        <!-- begin :: Row -->
                                                        <div class="row mb-10">

                                                            <!-- begin :: Column -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Brand') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="brand" id="brand-sp"
                                                                    data-placeholder="{{ __('Choose the brand') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}"
                                                                            {{ $brand->id == $order->car->brand_id ? 'selected' : '' }}>
                                                                            {{ $brand->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="brand"></p>
                                                            </div>
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Model') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="model" id="model-sp"
                                                                    data-placeholder="{{ __('Choose the model') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="{{ $order->car->model_id }}" selected>
                                                                        {{ $order->car->model->name }}
                                                                    </option>
                                                                    @if (isset($models))
                                                                        @foreach ($models as $model)
                                                                            <option value="{{ $model->id }}">
                                                                                {{ $model->name }} </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <p class="invalid-feedback" id="model"></p>
                                                            </div>
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Category') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="category" id="category-sp"
                                                                    data-placeholder="{{ __('Choose the category') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="{{ $order->car->category_id }}"
                                                                        selected>
                                                                        {{ $order->car->category->name }}
                                                                    </option>
                                                                    @if (isset($categories))
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}">
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <p class="invalid-feedback" id="category"></p>
                                                            </div>

                                                        </div>
                                                        <!-- end   :: Row -->
                                                        <!-- begin :: Row -->
                                                        <div class="row mb-10">
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Colors') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="colors-sp" name="color_id"
                                                                    data-placeholder="{{ __('Choose the color') }}"
                                                                    data-background-color="#000"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}"
                                                                            {{ $color->id == $order->car->color_id ? 'selected' : '' }}>
                                                                            {{ $color->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="colors"></p>

                                                            </div>

                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Year') }}</label>

                                                                <select class="form-select" data-control="select2"
                                                                    name="year"
                                                                    data-placeholder="{{ __('Choose the year') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @for ($year = Date('Y') + 1; $year >= 1800; $year--)
                                                                        <option value="{{ $year }}"
                                                                            {{ $year == $order->car->year ? 'selected' : '' }}>
                                                                            {{ $year }} </option>
                                                                    @endfor
                                                                </select>
                                                                <p class="invalid-feedback" id="year"></p>


                                                            </div>

                                                            <div class="col-md-4 fv-row mb-5">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('gear shifter') }}</label>

                                                                <select class="form-select" data-control="select2"
                                                                    name="gear_shifter"
                                                                    data-placeholder="{{ __('Choose the gear shifter') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="manual"
                                                                        {{ $order->car->gear_shifter == 'manual' ? 'selected' : '' }}>
                                                                        {{ __('manual') }}
                                                                    </option>
                                                                    <option value="automatic"
                                                                        {{ $order->car->gear_shifter == 'automatic' ? 'selected' : '' }}>
                                                                        {{ __('automatic') }}
                                                                    </option>
                                                                </select>
                                                                <p class="invalid-feedback" id="gear_shifter"></p>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="error-message" style="  color: red;"></div>
                                                    <div id="car-card" style="display: none;">
                                                        <div
                                                            style="display: flex; align-items: center; border-radius: 10px; border: 1px solid #ccc; padding: 10px; margin: 10px; max-width: 600px; margin: auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                                                            <div style="flex: 1; padding-right: 10px;">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h3 id="car-name"
                                                                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    </h3>
                                                                    <div
                                                                        style="margin-top: 3px; display: flex; flex-wrap: wrap;">
                                                                        <p id="car-brand"
                                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}"
                                                                            style="margin: 0; font-size: 0.9em; color: #888;">
                                                                        </p> -
                                                                        <p id="car-model"
                                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}"
                                                                            style="margin: 0; font-size: 0.9em; color: #888;">
                                                                        </p> -
                                                                        <p id="car-category"
                                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}"
                                                                            style="margin: 0; font-size: 0.9em; color: #888;">
                                                                        </p> -
                                                                        <p id="car-year"
                                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}"
                                                                            style="margin: 0; font-size: 0.9em; color: #888;">
                                                                        </p> -
                                                                        <p id="car-gear-shifter"
                                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}"
                                                                            style="margin: 0; font-size: 0.9em; color: #888;">
                                                                        </p>

                                                                    </div>
                                                                </div>
                                                                <div class="mt-5">
                                                                    <h5
                                                                        style="margin-bottom: 10px; color: #555; font-size: 1em; font-weight:bold;">
                                                                        {{ __('Price after VAT') }}
                                                                    </h5>
                                                                    <h3 id="car-price"
                                                                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                        <span id="price-amount"
                                                                            style="color: green;"></span>
                                                                        <span
                                                                            id="price-currency">{{ __('SAR') }}</span>
                                                                    </h3>
                                                                </div>
                                                            </div>

                                                            <div style="text-align: center; margin-left: 20px;">
                                                                <img id="car-image" src="" alt="Car Image"
                                                                    style="max-width: 150px; height: auto; border-radius: 10px;"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="error-message"
                                                        style="display: none; color: red; text-align: center; margin: 10px;">
                                                    </div>


                                                    <div id="error-message"
                                                        style="display: none; color: red; text-align: center; margin: 10px;">
                                                    </div>


                                                @endif



                                                <div class="separator separator-dashed my-4"></div>
                                                <div>
                                                    <h3 class="text-center mb-4" style="font-weight: 900">
                                                        {{ __('Personal Data') }}</h3>
                                                    <!--begin::Wrapper-->
                                                    <div class="row mb-10">
                                                        <div class="row">
                                                            <!-- begin :: Column -->
                                                            <!-- Client name -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Client name') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="client_name_inp" name="client_name"
                                                                        value="{{ $order->name }}"
                                                                        placeholder="example" />
                                                                    <label
                                                                        for="client_name_inp">{{ __('Client name') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="client_name"></p>
                                                            </div>


                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Phone') }}</label>
                                                                <div class="input-group mb-5">
                                                                    <span class="input-group-text"
                                                                        id="basic-addon1">+966</span>
                                                                    <input type="text" class="form-control"
                                                                        id="phone_inp" name="phone"
                                                                        value="{{ '0' . substr($order->phone, 3) }}"
                                                                        placeholder="{{ __('Enter the phone') }}"
                                                                        maxlength="10" />
                                                                    <p class="invalid-feedback" id="phone"></p>
                                                                </div>
                                                            </div>

                                                            <!-- end   :: Column -->

                                                            <!-- Gender -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Gender') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="gender_inp" name="sex"
                                                                    data-placeholder="{{ __('Gender') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value=""
                                                                        {{ is_null($order['sex']) ? 'selected' : '' }}>
                                                                    </option>
                                                                    <option value="male"
                                                                        {{ $order['sex'] == 'male' ? 'selected' : '' }}>
                                                                        {{ __('male') }}</option>
                                                                    <option value="female"
                                                                        {{ $order['sex'] == 'female' ? 'selected' : '' }}>
                                                                        {{ __('female') }}</option>
                                                                </select>
                                                                <p class="invalid-feedback" id="sex"></p>
                                                            </div>






                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Birthdate') }}</label>
                                                                <input name="birth_date" type='date'
                                                                    value="{{ $order['birth_date'] }}"
                                                                    class="form-select border-gray-300 border-1  me-4"
                                                                    placeholder="{{ __('Choose the date') }}" />
                                                                <p class="invalid-feedback" id="birth_date"></p>
                                                            </div>

                                                            <!-- City -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('City') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="city_inp_ind" name="city_id"
                                                                    data-placeholder="{{ __('City') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value=""
                                                                        {{ is_null($order['city_id']) ? 'selected' : '' }}>
                                                                    </option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{ $city->id }}"
                                                                            {{ $city->id == $order['city_id'] ? 'selected' : '' }}>
                                                                            {{ $city->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="city_id"></p>
                                                            </div>



                                                            <!-- Identity no -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Identity no') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="identity_number_inp" name="identity_no"
                                                                        value="{{ $order['identity_no'] ?? '' }}"
                                                                        placeholder="example" />
                                                                    <label
                                                                        for="identity_number_inp">{{ __('Identity no') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="identity_no"></p>
                                                            </div>

                                                            <!-- The Sector -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('The Sector') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="sector_inp" name="sector"
                                                                    data-placeholder="{{ __('The Sector') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value=""
                                                                        {{ is_null($order['orderDetailsCar']['sector_id']) ? 'selected' : '' }}>
                                                                    </option>
                                                                    @foreach ($sectors as $sector)
                                                                        <option value="{{ $sector->id }}"
                                                                            {{ $sector->id == $order['orderDetailsCar']['sector_id'] ? 'selected' : '' }}>
                                                                            {{ $sector->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="sector"></p>
                                                            </div>

                                                            <!-- Salary -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Salary') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="salary_inp" name="salary"
                                                                        value="{{ $order['orderDetailsCar']['salary'] ?? '' }}"
                                                                        placeholder="example" />
                                                                    <label for="salary_inp">{{ __('Salary') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="salary"></p>
                                                            </div>

                                                            <!-- Bank -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Bank') }}</label>
                                                                <div class="form-floating">
                                                                    <select class="form-select" data-control="select2"
                                                                        id="bank_inp_ind" name="bank"
                                                                        data-placeholder="{{ __('Bank') }}"
                                                                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                        <option value=""
                                                                            {{ is_null($order['orderDetailsCar']['bank_id']) ? 'selected' : '' }}>
                                                                        </option>
                                                                        @foreach ($banks as $bank)
                                                                            <option value="{{ $bank->id }}"
                                                                                {{ $bank->id == $order['orderDetailsCar']['bank_id'] ? 'selected' : '' }}>
                                                                                {{ $bank->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <p class="invalid-feedback" id="bank"></p>
                                                            </div>

                                                            <!-- Monthly needed -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Monthly needed') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="Monthly_cometment_inp"
                                                                        name="Monthly_cometment"
                                                                        value="{{ $order['orderDetailsCar']['commitments'] ?? '' }}"
                                                                        placeholder="{{ __('monthly cometment') }}" />
                                                                </div>
                                                                <p class="invalid-feedback" id="Monthly_cometment">
                                                                </p>
                                                            </div>

                                                            <!-- Nationality -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('The nationality') }}</label>
                                                                <div class="form-floating">
                                                                    <select class="form-select" data-control="select2"
                                                                        id="nationality_inp" name="nationality_id"
                                                                        data-placeholder="{{ __('The nationality') }}"
                                                                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                        <option value=""
                                                                            {{ is_null($order['nationality_id']) ? 'selected' : '' }}>
                                                                        </option>
                                                                        @foreach ($nationality as $nation)
                                                                            <option value="{{ $nation->id }}"
                                                                                {{ $nation->id == $order['nationality_id'] ? 'selected' : '' }}>
                                                                                {{ $nation->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <p class="invalid-feedback" id="nationality_id"></p>
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Email') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="email" class="form-control"
                                                                        id="email_inp" name="email"
                                                                        value="{{ $order['email'] ?? '' }}"
                                                                        placeholder="example@example.com" />
                                                                    <label for="email_inp">{{ __('Email') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="email"></p>
                                                            </div>
                                                        </div>

                                                        <span class="mb-4"></span>

                                                        <div
                                                            class="row mb-10 justify-content-center align-items-center text-center">
                                                            <!-- Department Loan -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Department Loan') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3  justify-content-center">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            value="true" name="department_loan"
                                                                            {{ $order['orderDetailsCar']['having_loan'] == 1 ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="department_loan" value="false"
                                                                            {{ $order['orderDetailsCar']['having_loan'] == 0 ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="department_loan">
                                                                </p>
                                                            </div>

                                                            <!-- Driving License -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Driving License') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3  justify-content-center">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="driving_license" value="true"
                                                                            {{ $order['orderDetailsCar']['driving_license'] == 'available' ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="driving_license" value="false"
                                                                            {{ $order['orderDetailsCar']['driving_license'] != 'available' ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="driving_license">
                                                                </p>
                                                            </div>

                                                            {{-- <!-- Have Life Problem -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Have Life Problem') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="have_life_problem" value="true"
                                                                            {{ $order['orderDetailsCar']['have_life_problem'] == 1 ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="have_life_problem" value="false"
                                                                            {{ $order['orderDetailsCar']['have_life_problem'] == 0 ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="have_life_problem"></p>
                                                            </div> --}}

                                                            <!-- Traffic Violations -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Traffic Violations') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3  justify-content-center">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="traffic_violations" value="true"
                                                                            {{ $order['orderDetailsCar']['traffic_violations'] == 1 ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="traffic_violations" value="false"
                                                                            {{ $order['orderDetailsCar']['traffic_violations'] == 0 ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="traffic_violations">
                                                                </p>
                                                            </div>


                                                        </div>
                                                        <!--end::Wrapper-->


                                                    </div>

                                                </div>
                                                <div class="separator separator-dashed my-4"></div>
                                                <div>
                                                    <h3 class="text-center mb-4" style="font-weight: 900">
                                                        {{ __('Finance Data') }}</h3>
                                                    <!-- begin :: Row -->
                                                    <!-- begin :: Row -->
                                                    <div class="row mb-10">
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row mb-5">

                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('The first installment') }}</label>

                                                            <select class="form-select" data-control="select2"
                                                                name="first_batch"
                                                                data-placeholder="{{ __('The first installment amount') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value=""
                                                                    {{ is_null($order['orderDetailsCar']['first_installment']) ? 'selected' : '' }}>
                                                                    {{ $order['orderDetailsCar']['first_installment'] }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '0' ? 'selected' : '' }}>
                                                                    0%</option>
                                                                <option value="5"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '5' ? 'selected' : '' }}>
                                                                    5%</option>
                                                                <option value="10"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '10' ? 'selected' : '' }}>
                                                                    10%</option>
                                                                <option value="15"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '15' ? 'selected' : '' }}>
                                                                    15%</option>
                                                                <option value="20"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '20' ? 'selected' : '' }}>
                                                                    20%</option>
                                                                <option value="25"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '25' ? 'selected' : '' }}>
                                                                    25%</option>
                                                                <option value="30"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '30' ? 'selected' : '' }}>
                                                                    30%</option>
                                                                <option value="35"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '35' ? 'selected' : '' }}>
                                                                    35%</option>
                                                                <option value="40"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '40' ? 'selected' : '' }}>
                                                                    40%</option>
                                                                <option value="45"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '45' ? 'selected' : '' }}>
                                                                    45%</option>
                                                                <option value="50"
                                                                    {{ $order['orderDetailsCar']['first_installment'] == '50' ? 'selected' : '' }}>
                                                                    50%</option>
                                                            </select>
                                                            <p class="invalid-feedback" id="first_batch"></p>

                                                        </div>
                                                        <!-- end   :: Column -->
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('The first installment amount') }}
                                                            </label>
                                                            <div class="form-floating">
                                                                <input style="direction: {{ isArabic() ? 'rtl' : '' }}"
                                                                    type="number"
                                                                    value="{{ $order['orderDetailsCar']['first_payment_value'] ?? 0 }}"
                                                                    class="form-control" id="first_payment_value_inp"
                                                                    name="first_payment_value" placeholder="example" />
                                                                <label
                                                                    for="first_payment_value">{{ __('The first installment amount') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="first_payment_value">
                                                            </p>
                                                        </div>
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row mb-5">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('installment years') }}</label>
                                                            <select class="form-select" data-control="select2"
                                                                name="installment"
                                                                data-placeholder="{{ __('installment years') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value=""
                                                                    {{ is_null($order['orderDetailsCar']['installment']) ? 'selected' : '' }}>
                                                                </option>
                                                                <option value="1"
                                                                    {{ $order['orderDetailsCar']['installment'] == '1' ? 'selected' : '' }}>
                                                                    1</option>
                                                                <option value="2"
                                                                    {{ $order['orderDetailsCar']['installment'] == '2' ? 'selected' : '' }}>
                                                                    2</option>
                                                                <option value="3"
                                                                    {{ $order['orderDetailsCar']['installment'] == '3' ? 'selected' : '' }}>
                                                                    3</option>
                                                                <option value="4"
                                                                    {{ $order['orderDetailsCar']['installment'] == '4' ? 'selected' : '' }}>
                                                                    4</option>
                                                                <option value="5"
                                                                    {{ $order['orderDetailsCar']['installment'] == '5' ? 'selected' : '' }}>
                                                                    5</option>
                                                            </select>
                                                            <p class="invalid-feedback" id="installment"></p>
                                                        </div>
                                                        <!-- end :: Column -->


                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row mb-5">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('The last installment') }}</label>
                                                            <select class="form-select" data-control="select2"
                                                                name="last_batch"
                                                                data-placeholder="{{ __('The last installment amount') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value=""
                                                                    {{ is_null($order['orderDetailsCar']['last_installment']) ? 'selected' : '' }}>
                                                                </option>
                                                                <option value="0"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '0' ? 'selected' : '' }}>
                                                                    0%</option>
                                                                <option value="5"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '5' ? 'selected' : '' }}>
                                                                    5%</option>
                                                                <option value="10"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '10' ? 'selected' : '' }}>
                                                                    10%</option>
                                                                <option value="15"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '15' ? 'selected' : '' }}>
                                                                    15%</option>
                                                                <option value="20"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '20' ? 'selected' : '' }}>
                                                                    20%</option>
                                                                <option value="25"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '25' ? 'selected' : '' }}>
                                                                    25%</option>
                                                                <option value="30"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '30' ? 'selected' : '' }}>
                                                                    30%</option>
                                                                <option value="35"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '35' ? 'selected' : '' }}>
                                                                    35%</option>
                                                                <option value="40"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '40' ? 'selected' : '' }}>
                                                                    40%</option>
                                                                <option value="45"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '45' ? 'selected' : '' }}>
                                                                    45%</option>
                                                                <option value="50"
                                                                    {{ $order['orderDetailsCar']['last_installment'] == '50' ? 'selected' : '' }}>
                                                                    50%</option>
                                                            </select>
                                                            <p class="invalid-feedback" id="last_batch"></p>
                                                        </div>
                                                        <!-- end :: Column -->

                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('The last installment amount') }}
                                                            </label>
                                                            <div class="form-floating">
                                                                <input style="direction: {{ isArabic() ? 'rtl' : '' }}"
                                                                    type="number"
                                                                    value="{{ $order['orderDetailsCar']['last_payment_value'] ?? 0 }}"
                                                                    class="form-control" id="last_payment_value_inp"
                                                                    name="last_payment_value" placeholder="example" />
                                                                <label
                                                                    for="last_payment_value">{{ __('The last installment amount') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="last_payment_value"></p>
                                                        </div>
                                                        <!-- begin :: Column -->

                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('Finance amounts') }}</label>
                                                            <div class="form-floating">
                                                                <input style="direction: {{ isArabic() ? 'rtl' : '' }}"
                                                                    type="number"
                                                                    value="{{ $order['orderDetailsCar']['finance_amount'] ?? 0 }}"
                                                                    class="form-control" id="finance_amount_inp"
                                                                    name="finance_amount" placeholder="example"
                                                                    min="0" step="0.01" />
                                                                <label
                                                                    for="finance_amount_inp">{{ __('Finance amounts') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="finance_amount"></p>
                                                        </div>
                                                        <!-- end :: Column -->


                                                        <!-- Salary -->
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('administrative fees') }}
                                                            </label>
                                                            <div class="form-floating">
                                                                <input style="direction: {{ isArabic() ? 'rtl' : '' }}"
                                                                    type="number"
                                                                    value="{{ $order['orderDetailsCar']['Adminstrative_fees'] ?? 0 }}"
                                                                    class="form-control" id="administrative_fees_inp"
                                                                    name="administrative_fees" placeholder="example" />
                                                                <label
                                                                    for="installment_inp">{{ __('Enter the administrative fees') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="administrative_fees">
                                                            </p>
                                                        </div>
                                                        <!-- end   :: Column -->

                                                        <!-- begin :: Column -->
                                                        <div class="col-md-4 fv-row">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('Monthely Installment') }}
                                                            </label>
                                                            <div class="form-floating">
                                                                <input style="direction: {{ isArabic() ? 'rtl' : '' }}"
                                                                    type="number"
                                                                    value="{{ $order['orderDetailsCar']['Monthly_installment'] ?? 0 }}"
                                                                    class="form-control" id="monthely_installment"
                                                                    name="monthely_installment" placeholder="example"
                                                                    min="0" step="0.01" />
                                                                <label
                                                                    for="monthely_installment">{{ __('Monthely Installment') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="monthely_installment">
                                                            </p>
                                                        </div>
                                                        <!-- end   :: Column -->

                                                    </div>
                                                    <!-- end   :: Row -->
                                                </div>



                                                <div class="d-flex justify-content-between border-top py-10 px-10">

                                                    <div>

                                                        <button type="submit"
                                                            class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 step-btn">

                                                            <span class="indicator-label">{{ __('Save') }}</span>

                                                            <!-- begin :: Indicator -->
                                                            <span class="indicator-progress">{{ __('Please wait ...') }}
                                                                <span
                                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                            </span>
                                                            <!-- end   :: Indicator -->

                                                        </button>

                                                    </div>
                                                </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($order['orderDetailsCar']['type'] == 'individual' && $order['orderDetailsCar']['payment_type'] == 'cash')
                            <div class="card-body pt-0">
                                <div class="row justify-content-center">
                                    <div class="col-xl-12">

                                        <form action="{{ route('dashboard.orders.final_approval') }}" class="form"
                                            method="post" id="submitted-form"
                                            data-redirection-url="{{ route('dashboard.orders.index') }}">

                                            <input type="hidden" name="old_order" value="{{ $order }}">

                                            <!--end::Form-->

                                            <div class="pb-8">
                                                @if ($order->car)

                                                    <div>
                                                        <h3 class="text-center mb-4" style="font-weight: 900">
                                                            {{ __('Car Data') }}</h3>
                                                        <!-- begin :: Row -->
                                                        <div class="row mb-10">

                                                            <!-- begin :: Column -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Brand') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="brand" id="brand-sp"
                                                                    data-placeholder="{{ __('Choose the brand') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}"
                                                                            {{ $brand->id == $order->car->brand_id ? 'selected' : '' }}>
                                                                            {{ $brand->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="brand"></p>
                                                            </div>
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Model') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="model" id="model-sp"
                                                                    data-placeholder="{{ __('Choose the model') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="{{ $order->car->model_id }}" selected>
                                                                        {{ $order->car->model->name }}
                                                                    </option>
                                                                    @if (isset($models))
                                                                        @foreach ($models as $model)
                                                                            <option value="{{ $model->id }}">
                                                                                {{ $model->name }} </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <p class="invalid-feedback" id="model"></p>
                                                            </div>
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Category') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="category" id="category-sp"
                                                                    data-placeholder="{{ __('Choose the category') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="{{ $order->car->category_id }}"
                                                                        selected>
                                                                        {{ $order->car->category->name }}
                                                                    </option>
                                                                    @if (isset($categories))
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}">
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <p class="invalid-feedback" id="category"></p>
                                                            </div>

                                                        </div>
                                                        <!-- end   :: Row -->
                                                        <!-- begin :: Row -->
                                                        <div class="row mb-10">
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Colors') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="colors-sp" name="color_id"
                                                                    data-placeholder="{{ __('Choose the color') }}"
                                                                    data-background-color="#000"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}"
                                                                            {{ $color->id == $order->car->color_id ? 'selected' : '' }}>
                                                                            {{ $color->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="colors"></p>

                                                            </div>

                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Year') }}</label>

                                                                <select class="form-select" data-control="select2"
                                                                    name="year"
                                                                    data-placeholder="{{ __('Choose the year') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @for ($year = Date('Y') + 1; $year >= 1800; $year--)
                                                                        <option value="{{ $year }}"
                                                                            {{ $year == $order->car->year ? 'selected' : '' }}>
                                                                            {{ $year }} </option>
                                                                    @endfor
                                                                </select>
                                                                <p class="invalid-feedback" id="year"></p>


                                                            </div>

                                                            <div class="col-md-4 fv-row mb-5">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('gear shifter') }}</label>

                                                                <select class="form-select" data-control="select2"
                                                                    name="gear_shifter"
                                                                    data-placeholder="{{ __('Choose the gear shifter') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="manual"
                                                                        {{ $order->car->gear_shifter == 'manual' ? 'selected' : '' }}>
                                                                        {{ __('manual') }}
                                                                    </option>
                                                                    <option value="automatic"
                                                                        {{ $order->car->gear_shifter == 'automatic' ? 'selected' : '' }}>
                                                                        {{ __('automatic') }}
                                                                    </option>
                                                                </select>
                                                                <p class="invalid-feedback" id="gear_shifter"></p>


                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif



                                                <div class="separator separator-dashed my-4"></div>
                                                <div>
                                                    <h3 class="text-center mb-4" style="font-weight: 900">
                                                        {{ __('Personal Data') }}</h3>
                                                    <!--begin::Wrapper-->
                                                    <div class="row mb-10">
                                                        <div class="row">
                                                            <!-- begin :: Column -->
                                                            <!-- Client name -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Client name') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="client_name_inp" name="client_name"
                                                                        value="{{ $order->name }}"
                                                                        placeholder="example" />
                                                                    <label
                                                                        for="client_name_inp">{{ __('Client name') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="client_name"></p>
                                                            </div>


                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Phone') }}</label>
                                                                <div class="input-group mb-5">
                                                                    <span class="input-group-text"
                                                                        id="basic-addon1">+966</span>
                                                                    <input type="text" class="form-control"
                                                                        id="phone_inp" name="phone"
                                                                        value="{{ '0' . substr($order->phone, 3) }}"
                                                                        placeholder="{{ __('Enter the phone') }}"
                                                                        maxlength="10" />
                                                                    <p class="invalid-feedback" id="phone"></p>
                                                                </div>
                                                            </div>

                                                            <!-- end   :: Column -->

                                                            <!-- Gender -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Gender') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="gender_inp" name="sex"
                                                                    data-placeholder="{{ __('Gender') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value=""
                                                                        {{ is_null($order['sex']) ? 'selected' : '' }}>
                                                                    </option>
                                                                    <option value="male"
                                                                        {{ $order['sex'] == 'male' ? 'selected' : '' }}>
                                                                        {{ __('male') }}</option>
                                                                    <option value="female"
                                                                        {{ $order['sex'] == 'female' ? 'selected' : '' }}>
                                                                        {{ __('female') }}</option>
                                                                </select>
                                                                <p class="invalid-feedback" id="sex"></p>
                                                            </div>






                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Birthdate') }}</label>
                                                                <input name="birth_date" type='date'
                                                                    value="{{ $order['birth_date'] }}"
                                                                    class="form-select border-gray-300 border-1  me-4"
                                                                    placeholder="{{ __('Choose the date') }}" />
                                                                <p class="invalid-feedback" id="birth_date"></p>
                                                            </div>

                                                            <!-- City -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('City') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="city_inp_ind" name="city_id"
                                                                    data-placeholder="{{ __('City') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value=""
                                                                        {{ is_null($order['city_id']) ? 'selected' : '' }}>
                                                                    </option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{ $city->id }}"
                                                                            {{ $city->id == $order['city_id'] ? 'selected' : '' }}>
                                                                            {{ $city->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="city_id"></p>
                                                            </div>



                                                            <!-- Identity no -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Identity no') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="identity_number_inp" name="identity_no"
                                                                        value="{{ $order['identity_no'] ?? '' }}"
                                                                        placeholder="example" />
                                                                    <label
                                                                        for="identity_number_inp">{{ __('Identity no') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="identity_no"></p>
                                                            </div>

                                                            <!-- The Sector -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('The Sector') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="sector_inp" name="sector"
                                                                    data-placeholder="{{ __('The Sector') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value=""
                                                                        {{ is_null($order['orderDetailsCar']['sector_id']) ? 'selected' : '' }}>
                                                                    </option>
                                                                    @foreach ($sectors as $sector)
                                                                        <option value="{{ $sector->id }}"
                                                                            {{ $sector->id == $order['orderDetailsCar']['sector_id'] ? 'selected' : '' }}>
                                                                            {{ $sector->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="sector"></p>
                                                            </div>

                                                            <!-- Salary -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Salary') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="salary_inp" name="salary"
                                                                        value="{{ $order['orderDetailsCar']['salary'] ?? '' }}"
                                                                        placeholder="example" />
                                                                    <label for="salary_inp">{{ __('Salary') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="salary"></p>
                                                            </div>

                                                            <!-- Bank -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Bank') }}</label>
                                                                <div class="form-floating">
                                                                    <select class="form-select" data-control="select2"
                                                                        id="bank_inp_ind" name="bank"
                                                                        data-placeholder="{{ __('Bank') }}"
                                                                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                        <option value=""
                                                                            {{ is_null($order['orderDetailsCar']['bank_id']) ? 'selected' : '' }}>
                                                                        </option>
                                                                        @foreach ($banks as $bank)
                                                                            <option value="{{ $bank->id }}"
                                                                                {{ $bank->id == $order['orderDetailsCar']['bank_id'] ? 'selected' : '' }}>
                                                                                {{ $bank->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <p class="invalid-feedback" id="bank"></p>
                                                            </div>

                                                            <!-- Monthly needed -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Monthly needed') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control"
                                                                        id="Monthly_cometment_inp"
                                                                        name="Monthly_cometment"
                                                                        value="{{ $order['orderDetailsCar']['commitments'] ?? '' }}"
                                                                        placeholder="{{ __('monthly cometment') }}" />
                                                                </div>
                                                                <p class="invalid-feedback" id="Monthly_cometment">
                                                                </p>
                                                            </div>

                                                            <!-- Nationality -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('The nationality') }}</label>
                                                                <div class="form-floating">
                                                                    <select class="form-select" data-control="select2"
                                                                        id="nationality_inp" name="nationality_id"
                                                                        data-placeholder="{{ __('The nationality') }}"
                                                                        data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                        <option value=""
                                                                            {{ is_null($order['nationality_id']) ? 'selected' : '' }}>
                                                                        </option>
                                                                        @foreach ($nationality as $nation)
                                                                            <option value="{{ $nation->id }}"
                                                                                {{ $nation->id == $order['nationality_id'] ? 'selected' : '' }}>
                                                                                {{ $nation->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <p class="invalid-feedback" id="nationality_id"></p>
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="col-md-4 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Email') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="email" class="form-control"
                                                                        id="email_inp" name="email"
                                                                        value="{{ $order['email'] ?? '' }}"
                                                                        placeholder="example@example.com" />
                                                                    <label for="email_inp">{{ __('Email') }}</label>
                                                                </div>
                                                                <p class="invalid-feedback" id="email"></p>
                                                            </div>
                                                        </div>

                                                        <span class="mb-4"></span>

                                                        <div
                                                            class="row mb-10 justify-content-center align-items-center text-center">
                                                            <!-- Department Loan -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Department Loan') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3  justify-content-center">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            value="true" name="department_loan"
                                                                            {{ $order['orderDetailsCar']['having_loan'] == 1 ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="department_loan" value="false"
                                                                            {{ $order['orderDetailsCar']['having_loan'] == 0 ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="department_loan">
                                                                </p>
                                                            </div>

                                                            <!-- Driving License -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Driving License') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3  justify-content-center">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="driving_license" value="true"
                                                                            {{ $order['orderDetailsCar']['driving_license'] == 'available' ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="driving_license" value="false"
                                                                            {{ $order['orderDetailsCar']['driving_license'] != 'available' ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="driving_license">
                                                                </p>
                                                            </div>

                                                            {{-- <!-- Have Life Problem -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Have Life Problem') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="have_life_problem" value="true"
                                                                            {{ $order['orderDetailsCar']['have_life_problem'] == 1 ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="have_life_problem" value="false"
                                                                            {{ $order['orderDetailsCar']['have_life_problem'] == 0 ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="have_life_problem"></p>
                                                            </div> --}}

                                                            <!-- Traffic Violations -->
                                                            <div class="col-md-3 fv-row">
                                                                <label class="fw-bold mb-2">
                                                                    <p>{{ __('Traffic Violations') }}</p>
                                                                </label>
                                                                <div class="d-flex gap-3  justify-content-center">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="traffic_violations" value="true"
                                                                            {{ $order['orderDetailsCar']['traffic_violations'] == 1 ? 'checked' : '' }}>
                                                                        {{ __('Yes') }}
                                                                    </label>
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="traffic_violations" value="false"
                                                                            {{ $order['orderDetailsCar']['traffic_violations'] == 0 ? 'checked' : '' }}>
                                                                        {{ __('No') }}
                                                                    </label>
                                                                </div>
                                                                <p class="invalid-feedback" id="traffic_violations">
                                                                </p>
                                                            </div>


                                                        </div>
                                                        <!--end::Wrapper-->


                                                    </div>

                                                </div>

                                            </div>



                                            <div class="d-flex justify-content-between border-top py-10 px-10">

                                                <div>

                                                    <button type="submit"
                                                        class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 step-btn">

                                                        <span class="indicator-label">{{ __('Save') }}</span>

                                                        <!-- begin :: Indicator -->
                                                        <span class="indicator-progress">{{ __('Please wait ...') }}
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                        <!-- end   :: Indicator -->

                                                    </button>

                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($order['orderDetailsCar']['type'] == 'organization')
                            <div class="card-body pt-0">
                                <div class="row justify-content-center">
                                    <div class="col-xl-12">

                                        <form action="{{ route('dashboard.orders.final_approval') }}" class="form"
                                            method="post" id="submitted-form"
                                            data-redirection-url="{{ route('dashboard.orders.index') }}">

                                            <input type="hidden" name="old_order" value="{{ $order }}">

                                            <!--end::Form-->

                                            <div class="pb-8">
                                                @if ($order->car)


                                                    <div>
                                                        <h3 class="text-center mb-4" style="font-weight: 900">
                                                            {{ __('Car Data') }}</h3>
                                                        <!-- begin :: Row -->
                                                        <div class="row mb-10">

                                                            <!-- begin :: Column -->
                                                            <div class="col-md-4 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Brand') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="brand" id="brand-sp"
                                                                    data-placeholder="{{ __('Choose the brand') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}"
                                                                            {{ $brand->id == $order->car->brand_id ? 'selected' : '' }}>
                                                                            {{ $brand->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="brand"></p>
                                                            </div>
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Model') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="model" id="model-sp"
                                                                    data-placeholder="{{ __('Choose the model') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="{{ $order->car->model_id }}"
                                                                        selected>
                                                                        {{ $order->car->model->name }}
                                                                    </option>
                                                                    @if (isset($models))
                                                                        @foreach ($models as $model)
                                                                            <option value="{{ $model->id }}">
                                                                                {{ $model->name }} </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <p class="invalid-feedback" id="model"></p>
                                                            </div>
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Category') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="category" id="category-sp"
                                                                    data-placeholder="{{ __('Choose the category') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="{{ $order->car->category_id }}"
                                                                        selected>
                                                                        {{ $order->car->category->name }}
                                                                    </option>
                                                                    @if (isset($categories))
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}">
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <p class="invalid-feedback" id="category"></p>
                                                            </div>

                                                        </div>
                                                        <!-- end   :: Row -->
                                                        <!-- begin :: Row -->
                                                        <div class="row mb-10">
                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Colors') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    id="colors-sp" name="color_id"
                                                                    data-placeholder="{{ __('Choose the color') }}"
                                                                    data-background-color="#000"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}"
                                                                            {{ $color->id == $order->car->color_id ? 'selected' : '' }}>
                                                                            {{ $color->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback" id="colors"></p>

                                                            </div>

                                                            <div class="col-md-4 fv-row">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Year') }}</label>

                                                                <select class="form-select" data-control="select2"
                                                                    name="year"
                                                                    data-placeholder="{{ __('Choose the year') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected></option>
                                                                    @for ($year = Date('Y') + 1; $year >= 1800; $year--)
                                                                        <option value="{{ $year }}"
                                                                            {{ $year == $order->car->year ? 'selected' : '' }}>
                                                                            {{ $year }} </option>
                                                                    @endfor
                                                                </select>
                                                                <p class="invalid-feedback" id="year"></p>


                                                            </div>

                                                            <div class="col-md-4 fv-row mb-5">

                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('gear shifter') }}</label>

                                                                <select class="form-select" data-control="select2"
                                                                    name="gear_shifter"
                                                                    data-placeholder="{{ __('Choose the gear shifter') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="manual"
                                                                        {{ $order->car->gear_shifter == 'manual' ? 'selected' : '' }}>
                                                                        {{ __('manual') }}
                                                                    </option>
                                                                    <option value="automatic"
                                                                        {{ $order->car->gear_shifter == 'automatic' ? 'selected' : '' }}>
                                                                        {{ __('automatic') }}
                                                                    </option>
                                                                </select>
                                                                <p class="invalid-feedback" id="gear_shifter"></p>


                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif




                                                <div class="separator separator-dashed my-4"></div>
                                                <div>
                                                    <h3 class="text-center mb-4" style="font-weight: 900">
                                                        {{ __('Personal Data') }}</h3>
                                                    <!--begin::Wrapper-->
                                                    <div class="row">
                                                        <!-- begin :: Column -->
                                                        <!-- Client name -->
                                                        <div class="col-md-4 fv-row">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('Organization Name') }}</label>
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                    id="organization_name_inp" name="organization_name"
                                                                    placeholder="example"
                                                                    value="{{ $order['orderDetailsCar']['organization_name'] }}" />
                                                                <label
                                                                    for="organization_name_inp">{{ __('Organization Name') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="organization_name"></p>
                                                        </div>

                                                        <!-- Phone number -->
                                                        <div class="col-md-4 fv-row">
                                                            <label class="fs-5 fw-bold mb-2">{{ __('Phone') }}</label>
                                                            <div class="input-group mb-5">
                                                                <span class="input-group-text"
                                                                    id="basic-addon1">+966</span>
                                                                <input type="text" class="form-control"
                                                                    id="phone_inp" name="phone"
                                                                    value="{{ '0' . substr($order->phone, 3) }}"
                                                                    placeholder="{{ __('Enter the phone') }}"
                                                                    maxlength="10" />
                                                                <p class="invalid-feedback" id="phone"></p>
                                                            </div>
                                                        </div>

                                                        <!-- City -->
                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('Organization Type') }}</label>
                                                            <select class="form-select" data-control="select2"
                                                                id="Organization_Type_inp" name="organization_type"
                                                                data-placeholder="{{ __('Organization Type') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected></option>
                                                                @foreach ($organizationTypes as $organizationType)
                                                                    <option value="{{ $organizationType->id }}"
                                                                        {{ $organizationType->id == $order['orderDetailsCar']['organization_type'] ? 'selected' : '' }}>
                                                                        {{ $organizationType->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="invalid-feedback" id="organization_type"></p>
                                                        </div>

                                                        <!-- City -->
                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label class="fs-5 fw-bold mb-2">{{ __('City') }}</label>
                                                            <select class="form-select" data-control="select2"
                                                                id="city_inp" name="city_id"
                                                                data-placeholder="{{ __('City') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected></option>
                                                                @foreach ($cities as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        {{ $city->id == $order['city_id'] ? 'selected' : '' }}>
                                                                        {{ $city->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="invalid-feedback" id="city_id_org"></p>
                                                        </div>


                                                        <!-- Identity no -->
                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('Organization Age') }}</label>
                                                            <div class="form-floating">
                                                                <input type="number" class="form-control"
                                                                    id="identity_number_inp" name="organization_age"
                                                                    placeholder="example"
                                                                    value="{{ $order['orderDetailsCar']['organization_age'] }}" />
                                                                <label
                                                                    for="organization_age">{{ __('Organization Age') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="organization_age"></p>
                                                        </div>
                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('commercial registration no') }}</label>
                                                            <div class="form-floating">
                                                                <input type="number" class="form-control"
                                                                    id="commercial_registration_no_inp"
                                                                    name="commercial_registration_no"
                                                                    placeholder="example"
                                                                    value="{{ $order['orderDetailsCar']['commercial_registration_no'] }}" />
                                                                <label
                                                                    for="commercial_registration_no_inp">{{ __('commercial registration no') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="commercial_registration_no">
                                                            </p>
                                                        </div>

                                                        <!-- City -->
                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('Organization Activity') }}</label>
                                                            <select class="form-select" data-control="select2"
                                                                id="organization_activity_inp"
                                                                name="organization_activity"
                                                                data-placeholder="{{ __('Organization Activity') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected></option>
                                                                @foreach ($organizationactivities as $organizationactivity)
                                                                    <option value="{{ $organizationactivity->id }}"
                                                                        {{ $organizationactivity->id == $order['orderDetailsCar']['organization_activity'] ? 'selected' : '' }}>
                                                                        {{ $organizationactivity->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="invalid-feedback" id="organization_activity">
                                                            </p>
                                                        </div>

                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('responspality person') }}</label>
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                    id="name_inp" name="name"
                                                                    placeholder="example"
                                                                    value="{{ $order['name'] }}" />
                                                                <label
                                                                    for="name_inp">{{ __('responspality person') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="name"></p>
                                                        </div>



                                                        <!-- Bank -->
                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label class="fs-5 fw-bold mb-2">{{ __('Bank') }}</label>
                                                            <div class="form-floating">
                                                                <select class="form-select" data-control="select2"
                                                                    id="bank_inp" name="bank_id"
                                                                    data-placeholder="{{ __('Bank') }}"
                                                                    data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                    <option value="" selected>

                                                                    </option>
                                                                    @foreach ($banks as $bank)
                                                                        <option value="{{ $bank->id }}"
                                                                            {{ $bank->id == $order['orderDetailsCar']['bank_id'] ? 'selected' : '' }}>

                                                                            {{ $bank->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <p class="invalid-feedback" id="bank_id"></p>
                                                        </div>

                                                        <div class="col-md-4 fv-row mb-3">
                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('car count') }}</label>
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                    id="car_count_inp" name="car_count"
                                                                    placeholder="example"
                                                                    value="{{ $order['orderDetailsCar']['car_count'] }}" />
                                                                <label for="car_count_inp">{{ __('car count') }}</label>
                                                            </div>
                                                            <p class="invalid-feedback" id="car_count"></p>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>



                                            <div class="d-flex justify-content-between border-top py-10 px-10">

                                                <div>

                                                    <button type="submit"
                                                        class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 step-btn">

                                                        <span class="indicator-label">{{ __('Save') }}</span>

                                                        <!-- begin :: Indicator -->
                                                        <span class="indicator-progress">{{ __('Please wait ...') }}
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                        <!-- end   :: Indicator -->

                                                    </button>

                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--end::Card body-->
                    </div>
                    <!--end::Order history-->
                </div>
                <!--end::Orders-->
                {{-- @endif --}}

            </div>


        </div>
        <!--end::Tab content-->
    </div>



    <!--end::Order details page-->
@endsection
@push('styles')
    <style>
        .card-header {
            background-color: rgb(0, 74, 111) !important;
        }

        .card-header h2 {
            color: white !important;
            font-weight: bold;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $('#order-status-sp').change(function() {

            let newStatus = $(this).val();
            let comment = '';

            inputAlert().then((result) => {

                comment = result.value[0] || '';

                if (result.isConfirmed) {
                    $.ajax({
                        url: "/dashboard/change-status/" + "{{ $order['id'] }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        data: {
                            status: newStatus,
                            comment
                        },
                        success: (response) => {
                            successAlert('{{ __('status has been changed successfully') }}')
                                .then(() => window.location.reload())
                        },
                        error: (error) => {
                            console.log(error)
                        },

                    });
                }

            });


        });
    </script>
    <script>
        $('#employee-sp').change(function() {
            let employee_id = $(this).val();
            let comment = '';

            inputAlert().then((result) => {

                comment = result.value[0] || '';

                if (result.isConfirmed) {
                    $.ajax({
                        url: "/dashboard/assigntoemployee/" + "{{ $order['id'] }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        data: {
                            employee_id: employee_id,
                            comment
                        },
                        success: (response) => {
                            successAlert(
                                    '{{ __('employees has been changed successfully') }}')
                                .then(() => window.location.reload())
                        },
                        error: (error) => {
                            console.log(error)
                        },

                    });
                }

            });


        });
    </script>
    <script>
        $(document).ready(function() {
            $('#brand-sp').on('change', function() {
                var brandId = $(this).val();
                // Clear the existing options in the "Model" dropdown
                $('#model-sp').empty();
                $('#model-sp').append('<option value="" selected></option>');

                if (brandId) {
                    // Make an AJAX request to fetch models based on the selected brand
                    $.ajax({
                        url: '/dashboard/get-models/' + brandId,
                        type: 'GET',
                        success: function(data) {
                            // Populate the "Model" dropdown with the fetched models
                            $.each(data, function(key, value) {
                                $('#model-sp').append('<option value="' + value
                                    .id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });

            $('#model-sp').on('change', function() {
                var modelId = $(this).val();

                // Clear the existing options in the "Category" dropdown
                $('#category-sp').empty();
                $('#category-sp').append('<option value="" selected></option>');

                if (modelId) {
                    // Make an AJAX request to fetch categories based on the selected model
                    $.ajax({
                        url: '/dashboard/get-categories/' + modelId,
                        type: 'GET',
                        success: function(data) {
                            // Populate the "Category" dropdown with the fetched categories
                            $.each(data, function(key, value) {
                                $('#category-sp').append('<option value="' + value
                                    .id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to send the AJAX request
            function filterCars() {
                // Get the selected values
                var brand = $('#brand-sp').val();
                var model = $('#model-sp').val();
                var category = $('#category-sp').val();
                var color = $('#colors-sp').val();
                var year = $('select[name="year"]').val();
                var gearShifter = $('select[name="gear_shifter"]').val();

                // Create the data object to send to the server
                var data = {
                    brand: brand,
                    model: model,
                    category: category,
                    color: color,
                    year: year,
                    gear_shifter: gearShifter,
                    _token: '{{ csrf_token() }}' // Include the CSRF token for Laravel
                };

                // Function to check if all values in the data object are not null or empty
                function isValidData(data) {
                    for (var key in data) {
                        if (data.hasOwnProperty(key) && (data[key] === null || data[key] === '')) {
                            return false;
                        }
                    }
                    return true;
                }

                // Send the AJAX request if data is valid
                if (isValidData(data)) {
                    $.ajax({
                        url: '/dashboard/filter-cars', // The endpoint for filtering cars
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response && response.data) {
                                if (response.data.length > 0) {
                                    var imagePath = "{{ asset('storage/Images/Cars') }}/" + response
                                        .data[0].main_image;
                                    $('#car-image').attr('src', imagePath);
                                    $('#car-name').text(response.data[0]
                                        .name); // Use the 'name' field from the response
                                    $('#price-amount').text(response.data[0]
                                        .selling_price); // Set the selling price
                                    $('#price-currency').text(
                                        "{{ __('SAR') }}"); // Set the currency text


                                    $('#car-brand').text(response.data[0].brand.name);
                                    $('#car-model').text(response.data[0].model.name);
                                    $('#car-category').text(response.data[0].category.name);
                                    $('#car-year').text(response.data[0].year);
                                    $('#car-gear-shifter').text(response.data[0].gear_shifter);
                                    $('#car-card').css('display', 'block');
                                    $('#error-message').css('display', 'none');
                                } else {
                                    // Handle the case where no car matches the criteria
                                    $('#car-card').css('display', 'none');
                                    $('#error-message').css('display', 'block').text(
                                        'No car found matching the criteria.');
                                }
                            } else {
                                console.log('No data in response');
                                // Handle the case where response.data is not present
                                $('#car-card').css('display', 'none');
                                $('#error-message').css('display', 'block').html(response.message ||
                                    'No data found.');

                            }
                        },
                        error: function(xhr) {
                            // Handle any errors
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    $('#car-card').css('display', 'none');
                    $('#error-message').css('display', 'none');

                    console.warn("Please fill in all the fields.");
                }


            }

            // Attach the change event to the form fields
            $('#brand-sp, #model-sp, #category-sp, #colors-sp, select[name="year"], select[name="gear_shifter"]')
                .change(function() {
                    filterCars();
                });
        });
    </script>
@endpush
