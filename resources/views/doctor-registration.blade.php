@extends('template.master')
@section('content')

    <!-- Form Start -->

    <section class="container-fluid">
        <div class="registration-form">
            <img src="{{ asset('assets/images/backgrounds/event-three-inner-bg.png') }}" alt="">
        </div>
        {{-- <div class="container contact-form">
        <form action="submit" class="">
            <h2 class="text-center section-title__title">Doctor Registration</h2>
            <h5 class="mb-3 text-center">Personal Information</h5>
            <div class="row">
                <div class="input-field col-lg-12 col-md-12">
                    <input type="text" name="name" required>
                    <label>Full Name</label>
                </div>
            </div>
            <div class="row">
                    <input name="profession" type="hidden" value="doctor" required>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="number" name="age" required>
                    <label>Age </label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="registrationNo" required>
                    <label>Registration No.</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="sex" required>
                    <label>Sex</label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="number" name="phone" required>
                    <label>Phone</label>
                </div>

            </div>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="email" name="email" required>
                    <label>Email Id</label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="relativeName" required>
                    <label>Relative Name</label>
                </div>
            </div>
            <h5 class="mt-2 mb-3 text-center">Address</h5>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="village" required>
                    <label>Village</label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="district" required>
                    <label>District</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="po" required>
                    <label>Post Office</label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="ps" required>
                    <label>Police Station</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="pin" required>
                    <label>Postal Code</label>
                </div>
            </div>

            <h5 class="mt-2 mb-3 text-center">Work Address</h5>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="buildingNo" required>
                    <label>Building No.</label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="landmark" required>
                    <label>Landmark</label>
                </div>

            </div>
            <div class="row">
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="workDistrict" required>
                    <label>Work District</label>
                </div>
                <div class="input-field col-lg-6 col-md-6">
                    <input type="text" name="state" required>
                    <label>State</label>
                </div>

            </div>
            <div class="row">
                <button class="text-center form-next-btn justify-content-center align-center " type="submit">
                    Submit
                </button>
            </div>



        </form>

    </div> --}}
        <div class="container mt-5">
            <div class="border-0 shadow-lg card rounded-4">
                <div class="p-4 card-body">
                    <h3 class="mb-4 text-center">Register Doctor & Worker</h3>

                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display success message -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- @if ($errors->has('email'))
                    <div class="alert alert-danger">
                        {{ $errors->first('email') }}
                    </div>
                @endif --}}

                    <form id="registerForm" action="{{ route('form.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <h5 class="mb-4 text-center">Personal Information</h5>

                            <div class="mb-3 col-md-6">
                                <label for="profession" class="form-label">Profession</label>
                                <select class="form-select" id="profession" name="designation"
                                    value="{{ old('designation') }}">
                                    <option value="">Select Profession</option>
                                    <option value="doctor" {{ old('designation') == 'doctor' ? 'selected' : '' }}>Doctor
                                    </option>
                                    <option value="worker" {{ old('designation') == 'worker' ? 'selected' : '' }}>Worker
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}">
                            </div>


                            {{-- <label for="name" class="form-label">Account Request</label> --}}
                            <input type="hidden" name="account_request" value="1">


                            <div class="mb-3 col-md-6">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="age" name="age"
                                    value="{{ old('age') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="sex" class="form-label">Sex</label>
                                <input type="text" class="form-control" id="sex" name="sex"
                                    value="{{ old('sex') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="consent_file" class="form-label">Consent File (Online Signature)</label>
                                <input type="file" class="form-control" id="consent_file" name="consent_file"
                                    accept="image/*">
                            </div>


                            <div class="mb-3 col-md-6">
                                <label for="village" class="form-label">Village</label>
                                <input type="text" class="form-control" id="village" name="village"
                                    value="{{ old('village') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="district" class="form-label">District</label>
                                <input type="text" class="form-control" id="district" name="district"
                                    value="{{ old('district') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="buildingNo" class="form-label">Building Number</label>
                                <input type="text" class="form-control" id="buildingNo" name="buildingNo"
                                    value="{{ old('buildingNo') }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state"
                                    value="{{ old('state') }}">
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Form End-->

@endsection
