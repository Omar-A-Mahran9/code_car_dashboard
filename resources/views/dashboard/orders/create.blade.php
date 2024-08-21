@extends('partials.dashboard.master')
@section('content')
    <!--begin::Card-->
    <div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10 mt-0"
        style="background-size: auto  calc(100% + 10rem); background-position: {{ isArabic() ? 'left' : 'right' }} ; background-image: url('{{ asset('dashboard-assets/media/illustrations/sketchy-1/10.png') }}')">
        <!--begin::Card header-->
        <div class="p-6">
            <div class="d-flex align-items-center">
                <!--begin::Icon-->
                <div class="symbol symbol-circle me-5">
                    <div class="symbol-label bg-transparent text-primary border border-secondary border-dashed">
                        <!--begin::Svg Icon | path: icons/duotune/abstract/abs020.svg-->
                        <span>
                            <i class="bi bi-plus fs-1 text-primary"></i>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                </div>
                <!--end::Icon-->
                <!--begin::Title-->
                <div class="d-flex flex-column">
                    <h2>{{ __('Add new Orders') }}</h2>
                </div>
                <!--end::Title-->
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pb-0">

            <!--begin::Navs-->
            <div class="d-flex overflow-auto h-55px">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">

                    <!--begin::Nav item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary me-6  setting-label active" id="finance-settings-label"
                            href="javascript:" onclick="changeSettingView('finance')">{{ __('finance') }}</a>
                    </li>
                    <!--end::Nav item-->



                </ul>
            </div>
            <!--begin::Navs-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!--begin::Form-->

    <!-- Begin :: finance Settings Card -->
    <input type="hidden" name="setting_type" value="finance" id="setting-type-inp">

    <!-- Begin :: finance Settings Card -->
    <div class="card card-flush setting-card mb-10" id="finance-settings-card">


        <!-- Begin :: Card body -->
        <div class="card-body  ">
            <div class="fv-row row  ">
                <div class="col-md-12 fv-row d-flex justify-content-center">
                    <div class="form-group column">
                        <!--<label class="col-12 fs-5 fw-bold">{{ __('Choose type') }}</label>-->
                        <div class="col-8 col-form-label">
                            <div class="radio-inline d-flex justify-content-start">
                                <div class="form-check form-check-custom form-check-solid mx-4">
                                    <input class="form-check-input" type="radio" value="individual" name="type"
                                        id="Individuals">
                                    <label class="form-check-label" for="Individuals">{{ __('Individuals') }}</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid mx-4">
                                    <input class="form-check-input" type="radio" value="organization" name="type"
                                        id="Organization">
                                    <label class="form-check-label" for="Organization">{{ __('Organization') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-danger invalid-feedback" id="type"></p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card card-flush">


            <!-- Individual Form -->
            <div id="individualForm" style="display: none;">

                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-xxl">
                        <!--begin::Card-->
                        <div class="card">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Stepper-->
                                <div class="stepper stepper-links d-flex flex-column pt-15" id="kt_create_account_stepper">
                                    <!--begin::Nav-->
                                    <div class="stepper-nav mb-5">
                                        <!--begin::Step 1-->
                                        <div class="stepper-item current" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('car details') }}</h3>
                                        </div>
                                        <!--end::Step 1-->
                                        <!--begin::Step 2-->
                                        <div class="stepper-item" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('Finance Options') }}</h3>
                                        </div>
                                        <!--end::Step 2-->
                                        <!--begin::Step 3-->
                                        <div class="stepper-item" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('personal information') }}</h3>
                                        </div>
                                        <!--end::Step 3-->
                                        <!--begin::Step 4-->
                                        <div class="stepper-item" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('Offers') }}</h3>
                                        </div>
                                        <!--end::Step 4-->
                                        <!--begin::Step 4-->
                                        <div class="stepper-item" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('compleate your order') }}</h3>
                                        </div>
                                        <!--end::Step 4-->

                                    </div>
                                    <!--end::Nav-->
                                    <!--begin::Form-->
                                    <form class="mx-auto mw-600px w-100 pt-15 pb-10" novalidate="novalidate"
                                        id="kt_create_account_form">
                                        <!--begin::Step 1-->
                                        <div class="current" data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <!-- begin :: Row -->
                                                <div class="row mb-10">
                                                    <input type="hidden" name="type" value="individual">
                                                    <input type="hidden" name="language" value={{ $lang }}>

                                                    <!-- begin :: Column -->
                                                    <div class="col-md-6 fv-row mb-3">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('Brand') }}</label>
                                                        <select class="form-select" data-control="select2" name="brand"
                                                            id="brand-sp" data-placeholder="{{ __('Choose the brand') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}">
                                                                    {{ $brand->name }} </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="brand"></p>
                                                    </div>
                                                    <!-- end   :: Column -->

                                                    <div class="col-md-6 fv-row mb-5">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('Model') }}</label>
                                                        <select class="form-select" data-control="select2" name="model"
                                                            id="model-sp"
                                                            data-placeholder="{{ __('Choose the model') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @if (isset($models))
                                                                @foreach ($models as $model)
                                                                    <option value="{{ $model->id }}">
                                                                        {{ $model->name }} </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <p class="invalid-feedback" id="model"></p>
                                                    </div>

                                                    <div class="col-md-6 fv-row mb-5">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('Category') }}</label>
                                                        <select class="form-select" data-control="select2"
                                                            name="category" id="category-sp"
                                                            data-placeholder="{{ __('Choose the category') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
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


                                                    <!-- begin :: Column -->
                                                    <div class="col-md-6 fv-row mb-5">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('Colors') }}</label>
                                                        <select class="form-select" data-control="select2" id="colors-sp"
                                                            name="color_id"
                                                            data-placeholder="{{ __('Choose the color') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <!--<option value="" selected></option>-->
                                                            <option value="" selected></option>
                                                            @foreach ($colors as $color)
                                                                <option value="{{ $color->id }}">
                                                                    {{ $color->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="color_id"></p>

                                                    </div>
                                                    <!-- end   :: Column -->

                                                    <!-- begin :: Column -->
                                                    <div class="col-md-6 fv-row mb-5">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('Year') }}</label>

                                                        <select class="form-select" data-control="select2" name="year"
                                                            data-placeholder="{{ __('Choose the year') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($years as $year)
                                                                <option value="{{ $year }}">
                                                                    {{ $year }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="year"></p>


                                                    </div>
                                                    <!-- end   :: Column -->
                                                    <!-- begin :: Column -->
                                                    <div class="col-md-6 fv-row mb-5">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('gear shifter') }}</label>

                                                        <select class="form-select" data-control="select2"
                                                            name="gear_shifter"
                                                            data-placeholder="{{ __('Choose the gear shifter') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>

                                                            <option value="manual"> {{ __('manual') }}
                                                            </option>
                                                            <option value="automatic"> {{ __('automatic') }}
                                                            </option>
                                                        </select>
                                                        <p class="invalid-feedback" id="gear_shifter"></p>


                                                    </div>
                                                    <!-- end   :: Column -->



                                                </div>
                                                <!-- end   :: Row -->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>

                                        <!--end::Step 2-->
                                        <!--begin::Step 3-->
                                        <div data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <!--begin::Heading-->
                                                <div class="">

                                                    <!-- begin :: Row -->
                                                    <div class="row mb-10">
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-12 fv-row mb-5">

                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('The first installment') }}</label>

                                                            <select class="form-select" data-control="select2"
                                                                name="first_batch"
                                                                data-placeholder="{{ __('The first installment amount') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected></option>
                                                                <option value="0">0%</option>

                                                                <option value="5">5%</option>
                                                                <option value="10">10%</option>
                                                                <option value="15">15%</option>
                                                                <option value="20">20%</option>
                                                                <option value="25">25%</option>
                                                                <option value="30">30%</option>
                                                                <option value="35">35%</option>
                                                                <option value="40">40%</option>
                                                                <option value="45">45%</option>
                                                                <option value="50">50%</option>
                                                            </select>
                                                            <p class="invalid-feedback" id="first_batch"></p>

                                                        </div>
                                                        <!-- end   :: Column -->
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-12 fv-row mb-5">

                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('installment years') }}</label>

                                                            <select class="form-select" data-control="select2"
                                                                name="installment"
                                                                data-placeholder="{{ __('installment years') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected></option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>

                                                            </select>
                                                            <p class="invalid-feedback" id="installment"></p>

                                                        </div>
                                                        <!-- end   :: Column -->
                                                        <!-- begin :: Column -->
                                                        <div class="col-md-12 fv-row mb-5">

                                                            <label
                                                                class="fs-5 fw-bold mb-2">{{ __('The last installment') }}</label>

                                                            <select class="form-select" data-control="select2"
                                                                name="last_batch"
                                                                data-placeholder="{{ __('The last installment amount') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected></option>
                                                                <option value="0">0%</option>

                                                                <option value="5">5%</option>
                                                                <option value="10">10%</option>
                                                                <option value="15">15%</option>
                                                                <option value="20">20%</option>
                                                                <option value="25">25%</option>
                                                                <option value="30">30%</option>
                                                                <option value="35">35%</option>
                                                                <option value="40">40%</option>
                                                                <option value="45">45%</option>
                                                                <option value="50">50%</option>
                                                            </select>
                                                            <p class="invalid-feedback" id="last_batch"></p>

                                                        </div>
                                                        <!-- end   :: Column -->
                                                    </div>
                                                    <!-- end   :: Row -->
                                                </div>
                                                <!--end::Heading-->

                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Step 3-->
                                        <!--begin::Step 4-->
                                        <div data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <div class="row">
                                                    <!-- begin :: Column -->
                                                    <!-- Client name -->
                                                    <div class="col-md-6 fv-row">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Client name') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                id="client_name_inp" name="client_name"
                                                                placeholder="example" />
                                                            <label for="client_name_inp">{{ __('Client name') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="client_name"></p>
                                                    </div>

                                        
                                                    <div class="col-md-6 fv-row">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('Phone') }}</label>
                                                        <div class="input-group mb-5">
                                                            <span class="input-group-text" id="basic-addon1">+966</span>
                                                            <input type="text" class="form-control" id="phone_inp"
                                                                name="phone" placeholder="{{ __('Enter the phone') }}"
                                                                maxlength="10" />
                                                            <p class="invalid-feedback" id="phone"></p>

                                                        </div>
                                                    </div>
                                                    <!-- end   :: Column -->

                                                    <!-- Gender -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Gender') }}</label>
                                                        <select class="form-select" data-control="select2"
                                                            id="gender_inp" name="sex"
                                                            data-placeholder="{{ __('Gender') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            <option value="male">{{ __('male') }}</option>
                                                            <option value="female">{{ __('female') }}</option>
                                                        </select>
                                                        <p class="invalid-feedback" id="sex"></p>
                                                    </div>





                                                    <div class="col-md-6 fv-row">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Birthdate') }}</label>
                                                        <input name="birth_date" type='date'
                                                            class="form-select border-gray-300 border-1  me-4"
                                                            placeholder="{{ __('Choose the date') }}" />
                                                        <p class="invalid-feedback" id="birth_date"></p>
                                                    </div>




                                                    <!-- City -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('City') }}</label>
                                                        <select class="form-select" data-control="select2"
                                                            id="city_inp_ind" name="city_id"
                                                            data-placeholder="{{ __('City') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}">
                                                                    {{ $city->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="city_id"></p>
                                                    </div>

                                                    <!-- Identity no -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Identity no') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                id="identity_number_inp" name="identity_no"
                                                                placeholder="example" />
                                                            <label
                                                                for="identity_number_inp">{{ __('Identity no') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="identity_no"></p>
                                                    </div>


                                                    <div class="col-md-6 fv-row">

                                                        <label class="fs-5 fw-bold mb-2">{{ __('The Sector') }}</label>

                                                        <select class="form-select" data-control="select2"
                                                            id="sector_inp" name="sector"
                                                            data-placeholder="{{ __('The Sector') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($sectors as $sector)
                                                                <option value="{{ $sector->id }}">{{ $sector->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="sector"></p>

                                                    </div>

                                                    <!-- Salary -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Salary') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="salary_inp"
                                                                name="salary" placeholder="example" />
                                                            <label for="salary_inp">{{ __('Salary') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="salary"></p>
                                                    </div>

                                                    <!-- Bank -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Bank') }}</label>
                                                        <div class="form-floating">
                                                            <select class="form-select" data-control="select2"
                                                                id="bank_inp_ind" name="bank"
                                                                data-placeholder="{{ __('Bank') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected>

                                                                </option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <p class="invalid-feedback" id="bank"></p>
                                                    </div>

                                                    <!-- Monthly needed -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('Monthly needed') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                id="Monthly_cometment_inp" name="Monthly_cometment"
                                                                placeholder="{{ __('monthly cometment') }}" />

                                                        </div>
                                                        <p class="invalid-feedback" id="Monthly_cometment"></p>
                                                    </div>

                                                    <!-- Nationality -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('The nationality') }}</label>
                                                        <div class="form-floating">
                                                            <select class="form-select" data-control="select2"
                                                                id="nationality_inp" name="nationality_id"
                                                                data-placeholder="{{ __('The nationality') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected>

                                                                </option>
                                                                @foreach ($nationality as $nation)
                                                                    <option value="{{ $nation->id }}">
                                                                        {{ $nation->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <p class="invalid-feedback" id="nationality_id"></p>
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Email') }}</label>
                                                        <div class="form-floating">
                                                            <input type="email" class="form-control" id="email_inp"
                                                                name="email" placeholder="example@example.com" />
                                                            <label for="email_inp">{{ __('Email') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="email"></p>
                                                    </div>

                                                </div>
                                                <hr class="mb-4">

                                                <div class="row mb-10">
                                                    <!-- Add new radio buttons here -->
                                                    <div class="col-md-3 fv-row">
                                                        <label class="fw-bold mb-2">
                                                            <p>{{ __('Department Loan') }}</p>
                                                        </label>
                                                        <div class="d-flex gap-3">
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    value="true" name="department_loan">
                                                                {{ __('Yes') }}
                                                            </label>
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="department_loan" value="false" checked>
                                                                {{ __('No') }}
                                                            </label>
                                                        </div>
                                                        <p class="invalid-feedback" id="department_loan"></p>
                                                    </div>
                                                    <div class="col-md-3 fv-row">
                                                        <label class="fw-bold mb-2">
                                                            <p>{{ __('Driving License') }}</p>
                                                        </label>
                                                        <div class="d-flex gap-3">
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="driving_license" value="true">
                                                                {{ __('Yes') }}
                                                            </label>
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="driving_license" value="false" checked>
                                                                {{ __('No') }}
                                                            </label>
                                                        </div>
                                                        <p class="invalid-feedback" id="driving_license"></p>
                                                    </div>
                                                    <div class="col-md-3 fv-row">
                                                        <label class="fw-bold mb-2">
                                                            <p>{{ __('Have Life Problem') }}</p>
                                                        </label>
                                                        <div class="d-flex gap-3">
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="have_life_problem" value="true">
                                                                {{ __('Yes') }}
                                                            </label>
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="have_life_problem" value="false" checked>
                                                                {{ __('No') }}
                                                            </label>
                                                        </div>
                                                        <p class="invalid-feedback" id="have_life_problem"></p>
                                                    </div>
                                                    <div class="col-md-3 fv-row">
                                                        <label class="fw-bold mb-2">
                                                            <p>{{ __('Traffic Violations') }}</p>
                                                        </label>
                                                        <div class="d-flex gap-3">
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="traffic_violations" value="true">
                                                                {{ __('Yes') }}
                                                            </label>
                                                            <label>
                                                                <input class="form-check-input" type="radio"
                                                                    name="traffic_violations" value="false" checked>
                                                                {{ __('No') }}
                                                            </label>
                                                        </div>
                                                        <p class="invalid-feedback" id="traffic_violations"></p>
                                                    </div>
                                                </div>



                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Step 4-->
                                        <!--begin::Step 5-->
                                        <div data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <div class="row row-cols-1 row-cols-md-2 g-5" id=offers-container>

                                                </div>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Step 5-->
                                        <!--begin::Step 5-->
                                        <div data-kt-stepper-element="content">

                                            <!-- end   :: Row -->
                                            <!--begin::Card title-->

                                            <div class="d-flex flex-column justify-content-center align-items-center"
                                                style="
											width: 100%;
										">
                                                <div class="d-flex flex-column" style="width:100%;"
                                                    id="details-container">

                                                </div>

                                                <!-- begin :: Row -->
                                                <div class="row mt-10">

                                                    <!-- begin :: Column -->
                                                    <div class="col-md-12 fv-row d-flex justify-content-evenly gap-5">

                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('upload Identity Card') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="identity_Card"
                                                                    image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>

                                                            </div>
                                                            <p class="invalid-feedback" id="identity_Card"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>

                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('Upload Identity License Card') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="License_Card"
                                                                    :image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>
                                                            </div>
                                                            <p class="invalid-feedback" id="License_Card"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>
                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('Upload License Hr Letter Image') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="Hr_Letter_Image"
                                                                    :image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>
                                                            </div>
                                                            <p class="invalid-feedback" id="Hr_Letter_Image"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>

                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('Upload Insurance Image') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="Insurance_Image"
                                                                    :image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>
                                                            </div>
                                                            <p class="invalid-feedback" id="Insurance_Image"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>

                                                    </div>
                                                    <!-- end   :: Column -->

                                                </div>
                                            </div>

                                        </div>
                                        <!--end::Step 5-->

                                        <!--begin::Actions-->
                                        <div class="d-flex flex-stack pt-15">
                                            <!--begin::Wrapper-->
                                            <div class="mr-2">
                                                <button type="button" class="btn btn-lg btn-light-primary me-3"
                                                    data-kt-stepper-action="previous">
                                                    <i
                                                        class="ki-outline ki-arrow-left fs-4 me-1"></i>{{ __('Back') }}</button>
                                            </div>
                                            <!--end::Wrapper-->
                                            <!--begin::Wrapper-->
                                            <div>
                                                <button type="button" class="btn btn-lg btn-primary me-3"
                                                    data-kt-stepper-action="submit">
                                                    <span class="indicator-label">{{ __('Submit') }}
                                                        <i class="ki-outline ki-arrow-right fs-3 ms-2 me-0"></i></span>
                                                    <span class="indicator-progress">{{ __('Please wait...') }}
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                                <button type="button" class="btn btn-lg btn-primary"
                                                    data-kt-stepper-action="next">{{ __('Continue') }}
                                                </button>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Stepper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content container-->
                </div>
                <!--end::Content-->
            </div>

            <!-- Organization Form -->
            <div id="organizationForm" style="display: none;">
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-xxl">
                        <!--begin::Card-->
                        <div class="card">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Stepper-->
                                <div class="stepper stepper-links d-flex flex-column pt-15"
                                    id="kt_create_account_stepper_organization">
                                    <!--begin::Nav-->
                                    <div class="stepper-nav mb-5">
                                        <!--begin::Step 1-->
                                        <div class="stepper-item current" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('car details') }}</h3>
                                        </div>
                                        <!--end::Step 1-->

                                        <!--begin::Step 3-->
                                        <div class="stepper-item" data-kt-stepper-element="nav">
                                            <h3 class="stepper-title">{{ __('personal information') }}</h3>
                                        </div>
                                        <!--end::Step 3-->
                                        <!--begin::Step 4-->


                                    </div>
                                    <!--end::Nav-->
                                    <!--begin::Form-->
                                    <form class="mx-auto mw-600px w-100 pt-15 pb-10" novalidate="novalidate"
                                        id="kt_create_account_form_organization">
                                        <!--begin::Step 1-->
                                        <div class="current" data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <!-- begin :: Row -->
                                                <input type="hidden" name="type" value="organization">
                                                <input type="hidden" name="language" value={{ $lang }}>

                                                <div id="cars-container">
                                                    <div id="car-0">
                                                        <div class="row mb-10">
                                                            <!-- Each car starts here -->
                                                            <div class="d-flex justify-content-end">

                                                            </div>

                                                            <div class="col-md-6 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Brand') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[0][brand]"
                                                                    data-placeholder="{{ __('Choose the brand') }}"
                                                                    id="barnd_org-0" onchange=getmodels(this,0)>
                                                                    <option value="" selected></option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}">
                                                                            {{ $brand->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Model') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[0][model]" id="model_org-0"
                                                                    data-placeholder="{{ __('Choose the model') }}">
                                                                    <option value="" selected></option>
                                                                    <!-- Models will be populated dynamically based on brand selection -->
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Colors') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[0][color]"
                                                                    data-placeholder="{{ __('Choose the color') }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}">
                                                                            {{ $color->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Year') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[0][year]"
                                                                    data-placeholder="{{ __('Choose the year') }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($years as $year)
                                                                        <option value="{{ $year }}">
                                                                            {{ $year }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Gear Shifter') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[0][gear_shifter]"
                                                                    data-placeholder="{{ __('Choose the gear shifter') }}">
                                                                    <option value="" selected></option>
                                                                    <option value="manual">{{ __('Manual') }}</option>
                                                                    <option value="automatic">{{ __('Automatic') }}
                                                                    </option>
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row mb-5">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Number of Cars') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                        name="cars[0][car_count]" placeholder="example" />
                                                                </div>
                                                                <p class="invalid-feedback"></p>
                                                            </div>
                                                            <p class="invalid-feedback" id="error-0"></p>

                                                            <!-- Each car ends here -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="invalid-feedback" id='cars'></p>

                                                <div id="increment">
                                                    <button type="button"
                                                        class="add d-flex align-items-center gap-2 mb-3"
                                                        id="add-car-btn-0" onclick="appendRow(0)"
                                                        style="cursor: pointer; background: none; border: none;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 16 16" fill="none">
                                                            <path
                                                                d="M11.9997 0.833374H3.99968C2.25077 0.833374 0.833008 2.25114 0.833008 4.00004V12C0.833008 13.7489 2.25077 15.1667 3.99968 15.1667H11.9997C13.7486 15.1667 15.1663 13.7489 15.1663 12V4.00004C15.1663 2.25114 13.7486 0.833374 11.9997 0.833374Z"
                                                                fill="#005B27"></path>
                                                            <path
                                                                d="M7.99967 11.3334C7.82286 11.3334 7.65329 11.2632 7.52827 11.1382C7.40325 11.0131 7.33301 10.8436 7.33301 10.6667V5.33341C7.33301 5.1566 7.40325 4.98703 7.52827 4.86201C7.65329 4.73699 7.82286 4.66675 7.99967 4.66675C8.17649 4.66675 8.34605 4.73699 8.47108 4.86201C8.5961 4.98703 8.66634 5.1566 8.66634 5.33341V10.6667C8.66634 10.8436 8.5961 11.0131 8.47108 11.1382C8.34605 11.2632 8.17649 11.3334 7.99967 11.3334Z"
                                                                fill="white"></path>
                                                            <path
                                                                d="M5.33268 8.66671C5.15587 8.66671 4.9863 8.59647 4.86128 8.47144C4.73625 8.34642 4.66602 8.17685 4.66602 8.00004C4.66602 7.82323 4.73625 7.65366 4.86128 7.52864C4.9863 7.40361 5.15587 7.33337 5.33268 7.33337H10.666C10.8428 7.33337 11.0124 7.40361 11.1374 7.52864C11.2624 7.65366 11.3327 7.82323 11.3327 8.00004C11.3327 8.17685 11.2624 8.34642 11.1374 8.47144C11.0124 8.59647 10.8428 8.66671 10.666 8.66671H5.33268Z"
                                                                fill="white"></path>
                                                        </svg>
                                                        <span
                                                            style="color: #005B27; font-size: 14px;">{{ __('Add New Car') }}</span>
                                                    </button>
                                                </div>
                                                <!-- end   :: Row -->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>

                                        <!--end::Step 2-->

                                        <!--begin::Step 4-->
                                        <div data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <div class="row">
                                                    <!-- begin :: Column -->
                                                    <!-- Client name -->
                                                    <div class="col-md-6 fv-row">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('Organization Name') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                id="organization_name_inp" name="organization_name"
                                                                placeholder="example" />
                                                            <label
                                                                for="organization_name_inp">{{ __('Organization Name') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="organization_name"></p>
                                                    </div>

                                                    <!-- Phone number -->
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Phone number') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                id="phone_number_inp" name="phone"
                                                                placeholder="example" />
                                                            <label
                                                                for="phone_number_inp">{{ __('Phone number') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="phone_org"></p>
                                                    </div>






                                                    <!-- City -->
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('Organization Type') }}</label>
                                                        <select class="form-select" data-control="select2"
                                                            id="Organization_Type_inp" name="organization_type"
                                                            data-placeholder="{{ __('Organization Type') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($organizationTypes as $organizationType)
                                                                <option value="{{ $organizationType->id }}">
                                                                    {{ $organizationType->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="organization_type"></p>
                                                    </div>
                                                    <!-- City -->
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('City') }}</label>
                                                        <select class="form-select" data-control="select2" id="city_inp"
                                                            name="city_id" data-placeholder="{{ __('City') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}">
                                                                    {{ $city->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="city_id_org"></p>
                                                    </div>


                                                    <!-- Identity no -->
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('Organization Age') }}</label>
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control"
                                                                id="identity_number_inp" name="organization_age"
                                                                placeholder="example" />
                                                            <label
                                                                for="organization_age">{{ __('Organization Age') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="organization_age"></p>
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('commercial registration no') }}</label>
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control"
                                                                id="commercial_registration_no_inp"
                                                                name="commercial_registration_no" placeholder="example" />
                                                            <label
                                                                for="commercial_registration_no_inp">{{ __('commercial registration no') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="commercial_registration_no"></p>
                                                    </div>

                                                    <!-- City -->
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('Organization Activity') }}</label>
                                                        <select class="form-select" data-control="select2"
                                                            id="organization_activity_inp" name="organization_activity"
                                                            data-placeholder="{{ __('Organization Activity') }}"
                                                            data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                            <option value="" selected></option>
                                                            @foreach ($organizationactivities as $organizationactivity)
                                                                <option value="{{ $organizationactivity->id }}">
                                                                    {{ $organizationactivity->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <p class="invalid-feedback" id="organization_activity"></p>
                                                    </div>

                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label
                                                            class="fs-5 fw-bold mb-2">{{ __('responspality person') }}</label>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="name_inp"
                                                                name="name" placeholder="example" />
                                                            <label
                                                                for="name_inp">{{ __('responspality person') }}</label>
                                                        </div>
                                                        <p class="invalid-feedback" id="name"></p>
                                                    </div>



                                                    <!-- Bank -->
                                                    <div class="col-md-6 fv-row mb-3">
                                                        <label class="fs-5 fw-bold mb-2">{{ __('Bank') }}</label>
                                                        <div class="form-floating">
                                                            <select class="form-select" data-control="select2"
                                                                id="bank_inp" name="bank_id"
                                                                data-placeholder="{{ __('Bank') }}"
                                                                data-dir="{{ isArabic() ? 'rtl' : 'ltr' }}">
                                                                <option value="" selected>

                                                                </option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <p class="invalid-feedback" id="bank_id"></p>
                                                    </div>

                                                </div>


                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Step 4-->
                                        <!--begin::Step 5-->
                                        <div data-kt-stepper-element="content">
                                            <!--begin::Wrapper-->
                                            <div class="w-100">
                                                <div class="row row-cols-1 row-cols-md-2 g-5" id=offers-container>

                                                </div>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Step 5-->
                                        <!--begin::Step 5-->
                                        <div data-kt-stepper-element="content">

                                            <!-- end   :: Row -->
                                            <!--begin::Card title-->

                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <div class="d-flex flex-column" style="width:100%;"
                                                    id="details-container">

                                                </div>

                                                <!-- begin :: Row -->
                                                <div class="row mt-10">

                                                    <!-- begin :: Column -->
                                                    <div class="col-md-12 fv-row d-flex justify-content-evenly gap-3">

                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('upload Identity Card') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="identity_Card"
                                                                    image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>

                                                            </div>
                                                            <p class="invalid-feedback" id="identity_Card"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>

                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('Upload Identity License Card') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="License_Card"
                                                                    :image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>
                                                            </div>
                                                            <p class="invalid-feedback" id="License_Card"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>
                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('Upload License Hr Letter Image') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="Hr_Letter_Image"
                                                                    :image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>
                                                            </div>
                                                            <p class="invalid-feedback" id="Hr_Letter_Image"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>

                                                        <div class="d-flex flex-column align-items-center">
                                                            <!-- begin :: Upload image component -->
                                                            <label
                                                                class="text-center fw-bold mb-4">{{ __('Upload Insurance Image') }}</label>
                                                            <div>
                                                                <x-dashboard.upload-image-inp name="Insurance_Image"
                                                                    :image="null" :directory="null"
                                                                    placeholder="default.jpg"
                                                                    type="editable"></x-dashboard.upload-image-inp>
                                                            </div>
                                                            <p class="invalid-feedback" id="Insurance_Image"></p>
                                                            <!-- end   :: Upload image component -->
                                                        </div>

                                                    </div>
                                                    <!-- end   :: Column -->

                                                </div>
                                            </div>

                                        </div>
                                        <!--end::Step 5-->

                                        <!--begin::Actions-->
                                        <div class="d-flex flex-stack pt-15">
                                            <!--begin::Wrapper-->
                                            <div class="mr-2">
                                                <button type="button" class="btn btn-lg btn-light-primary me-3"
                                                    data-kt-stepper-action="previous">
                                                    <i
                                                        class="ki-outline ki-arrow-left fs-4 me-1"></i>{{ __('Back') }}</button>
                                            </div>
                                            <!--end::Wrapper-->
                                            <!--begin::Wrapper-->
                                            <div>
                                                <button type="button" class="btn btn-lg btn-primary me-3"
                                                    data-kt-stepper-action="submit">
                                                    <span class="indicator-label">{{ __('Submit') }}
                                                        <i class="ki-outline ki-arrow-right fs-3 ms-2 me-0"></i></span>
                                                    <span class="indicator-progress">{{ __('Please wait...') }}
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                                <button type="button" class="btn btn-lg btn-primary"
                                                    data-kt-stepper-action="next">{{ __('Continue') }}
                                                </button>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Stepper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content container-->
                </div>
                <!--end::Content-->
            </div>

        </div>
        <!-- End   :: Card body -->

    </div>
    <!-- End   :: finance Settings Card -->










@endsection
@push('scripts')
    <script>
        function appendRow(num) {
            $new_number = parseInt(num) + 1;
            $append_text = `<div id="car-${$new_number}">
                                <div class="row mb-10">
                                                            <!-- Each car starts here -->
                                                            <div class="d-flex justify-content-end">
                                                              <button type="button"
                                                                    class="remove-car-btn text-danger fw-bold"
                                                                    style="text-decoration: none; background:none; border:none;"
                                                                    onclick=removeRow(${$new_number})>Remove</button>
                                                            </div>

                                                            <div class="col-md-6 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Brand') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[${$new_number}][brand]"
                                                                    data-placeholder="{{ __('Choose the brand') }}"
                                                                    id="barnd_org-${$new_number}" onchange=getmodels(this,${$new_number})>
                                                                    <option value="" selected></option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}">
                                                                            {{ $brand->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Model') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[${$new_number}][model]" id="model_org-${$new_number}"
                                                                    data-placeholder="{{ __('Choose the model') }}">
                                                                    <option value="" selected></option>
                                                                    <!-- Models will be populated dynamically based on brand selection -->
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Colors') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[${$new_number}][color]"
                                                                    data-placeholder="{{ __('Choose the color') }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}">
                                                                            {{ $color->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Year') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[${$new_number}][year]"
                                                                    data-placeholder="{{ __('Choose the year') }}">
                                                                    <option value="" selected></option>
                                                                    @foreach ($years as $year)
                                                                        <option value="{{ $year }}">
                                                                            {{ $year }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Gear Shifter') }}</label>
                                                                <select class="form-select" data-control="select2"
                                                                    name="cars[${$new_number}][gear_shifter]"
                                                                    data-placeholder="{{ __('Choose the gear shifter') }}">
                                                                    <option value="" selected></option>
                                                                    <option value="manual">{{ __('Manual') }}</option>
                                                                    <option value="automatic">{{ __('Automatic') }}
                                                                    </option>
                                                                </select>
                                                                <p class="invalid-feedback"></p>
                                                            </div>

                                                            <div class="col-md-6 fv-row">
                                                                <label
                                                                    class="fs-5 fw-bold mb-2">{{ __('Number of Cars') }}</label>
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control"
                                                                        name="cars[${$new_number}][car_count]" placeholder="example" />
                                                                </div>
                                                                <p class="invalid-feedback"></p>
                                                            </div>
                                                            <!-- Each car ends here -->
                                                                <p class="invalid-feedback" id="error-${$new_number}"></p>
                                                        </div>
                                                    </div>`
            $($append_text).insertAfter(`#car-${num}`);
            $(`#add-car-btn-${num}`).remove();
            $("#increment").append(
                ` <button type="button"
                                                        class="add d-flex align-items-center gap-2 mb-3"
                                                        id="add-car-btn-${$new_number}" onclick="appendRow(${$new_number})"
                                                        style="cursor: pointer; background: none; border: none;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 16 16" fill="none">
                                                            <path
                                                                d="M11.9997 0.833374H3.99968C2.25077 0.833374 0.833008 2.25114 0.833008 4.00004V12C0.833008 13.7489 2.25077 15.1667 3.99968 15.1667H11.9997C13.7486 15.1667 15.1663 13.7489 15.1663 12V4.00004C15.1663 2.25114 13.7486 0.833374 11.9997 0.833374Z"
                                                                fill="#005B27"></path>
                                                            <path
                                                                d="M7.99967 11.3334C7.82286 11.3334 7.65329 11.2632 7.52827 11.1382C7.40325 11.0131 7.33301 10.8436 7.33301 10.6667V5.33341C7.33301 5.1566 7.40325 4.98703 7.52827 4.86201C7.65329 4.73699 7.82286 4.66675 7.99967 4.66675C8.17649 4.66675 8.34605 4.73699 8.47108 4.86201C8.5961 4.98703 8.66634 5.1566 8.66634 5.33341V10.6667C8.66634 10.8436 8.5961 11.0131 8.47108 11.1382C8.34605 11.2632 8.17649 11.3334 7.99967 11.3334Z"
                                                                fill="white"></path>
                                                            <path
                                                                d="M5.33268 8.66671C5.15587 8.66671 4.9863 8.59647 4.86128 8.47144C4.73625 8.34642 4.66602 8.17685 4.66602 8.00004C4.66602 7.82323 4.73625 7.65366 4.86128 7.52864C4.9863 7.40361 5.15587 7.33337 5.33268 7.33337H10.666C10.8428 7.33337 11.0124 7.40361 11.1374 7.52864C11.2624 7.65366 11.3327 7.82323 11.3327 8.00004C11.3327 8.17685 11.2624 8.34642 11.1374 8.47144C11.0124 8.59647 10.8428 8.66671 10.666 8.66671H5.33268Z"
                                                                fill="white"></path>
                                                        </svg>
                                                        <span
                                                            style="color: #005B27; font-size: 14px;">{{ __('Add New Car') }}</span>
                                                    </button>`);
        }

        function getmodels(element, num) {
            var brandId = $(element).val();

            // Clear the existing options in the "Model" dropdown
            $(`#model_org-${num}`).empty();
            $(`#model_org-${num}`).append('<option value="" selected></option>');

            if (brandId) {
                // Make an AJAX request to fetch models based on the selected brand
                $.ajax({
                    url: '/dashboard/get-models/' + brandId,
                    type: 'GET',
                    success: function(data) {
                        // Populate the "Model" dropdown with the fetched models
                        $.each(data, function(key, value) {

                            $(`#model_org-${num}`).append('<option value="' + value
                                .id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            }
        }

        function removeRow(num) {
            $new_number = parseInt(num) - 1;

            $(`#car-${num}`).remove();
            $(`#add-car-btn-${num}`).remove();
            $("#increment").append(
                ` <button type="button"
                                                        class="add d-flex align-items-center gap-2 mb-3"
                                                        id="add-car-btn-${$new_number}" onclick="appendRow(${$new_number})"
                                                        style="cursor: pointer; background: none; border: none;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 16 16" fill="none">
                                                            <path
                                                                d="M11.9997 0.833374H3.99968C2.25077 0.833374 0.833008 2.25114 0.833008 4.00004V12C0.833008 13.7489 2.25077 15.1667 3.99968 15.1667H11.9997C13.7486 15.1667 15.1663 13.7489 15.1663 12V4.00004C15.1663 2.25114 13.7486 0.833374 11.9997 0.833374Z"
                                                                fill="#005B27"></path>
                                                            <path
                                                                d="M7.99967 11.3334C7.82286 11.3334 7.65329 11.2632 7.52827 11.1382C7.40325 11.0131 7.33301 10.8436 7.33301 10.6667V5.33341C7.33301 5.1566 7.40325 4.98703 7.52827 4.86201C7.65329 4.73699 7.82286 4.66675 7.99967 4.66675C8.17649 4.66675 8.34605 4.73699 8.47108 4.86201C8.5961 4.98703 8.66634 5.1566 8.66634 5.33341V10.6667C8.66634 10.8436 8.5961 11.0131 8.47108 11.1382C8.34605 11.2632 8.17649 11.3334 7.99967 11.3334Z"
                                                                fill="white"></path>
                                                            <path
                                                                d="M5.33268 8.66671C5.15587 8.66671 4.9863 8.59647 4.86128 8.47144C4.73625 8.34642 4.66602 8.17685 4.66602 8.00004C4.66602 7.82323 4.73625 7.65366 4.86128 7.52864C4.9863 7.40361 5.15587 7.33337 5.33268 7.33337H10.666C10.8428 7.33337 11.0124 7.40361 11.1374 7.52864C11.2624 7.65366 11.3327 7.82323 11.3327 8.00004C11.3327 8.17685 11.2624 8.34642 11.1374 8.47144C11.0124 8.59647 10.8428 8.66671 10.666 8.66671H5.33268Z"
                                                                fill="white"></path>
                                                        </svg>
                                                        <span
                                                            style="color: #005B27; font-size: 14px;">{{ __('Add New Car') }}</span>
                                                    </button>`);
            $(`#add-car-btn-${num-1}`).remove();

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


    <script src="{{ asset('dashboard-assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/custom/utilities/modals/users-search.js') }}"></script>









    <script>
        let changeSettingView = (tab) => {

            $('.setting-card').hide();
            $('.setting-label').removeClass('active');

            $("#" + tab + '-settings-card').show()
            $("#" + tab + '-settings-label').addClass('active')

            $("#setting-type-inp").val(tab);
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const individualRadio = document.getElementById('Individuals');
            const organizationRadio = document.getElementById('Organization');
            const individualForm = document.getElementById('individualForm');
            const organizationForm = document.getElementById('organizationForm');

            individualRadio.addEventListener('change', function() {
                if (this.checked) {
                    individualForm.style.display = 'block';
                    organizationForm.style.display = 'none';
                    organizationRadio.checked = false; // Ensure organizationRadio is unchecked
                    loadScript(
                        '{{ asset('dashboard-assets/js/custom/utilities/modals/offer-dashboard.js') }}'
                    );

                }
            });
            organizationRadio.addEventListener('change', function() {
                if (this.checked) {
                    individualForm.style.display = 'none';
                    organizationForm.style.display = 'block';
                    individualRadio.checked = false; // Ensure individualRadio is unchecked
                    loadScript(
                        '{{ asset('dashboard-assets/js/custom/utilities/modals/organization-order.js') }}'
                    );

                }
            });

        });
        // Function to dynamically load scripts
        function loadScript(url) {
            const script = document.createElement('script');
            script.src = url;
            document.head.appendChild(script);
        }
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

            // $('#barnd_org-0').on('change', function() {
            //     var brandId = $(this).val();
            //     // Clear the existing options in the "Model" dropdown
            //     $('#model_org').empty();
            //     $('#model_org').append('<option value="" selected></option>');

            //     if (brandId) {
            //         // Make an AJAX request to fetch models based on the selected brand
            //         $.ajax({
            //             url: '/dashboard/get-models/' + brandId,
            //             type: 'GET',
            //             success: function(data) {
            //                 // Populate the "Model" dropdown with the fetched models
            //                 $.each(data, function(key, value) {

            //                     $('#model_org').append('<option value="' + value
            //                         .id +
            //                         '">' + value.name + '</option>');
            //                 });
            //             }
            //         });
            //     }
            // });

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
@endpush
