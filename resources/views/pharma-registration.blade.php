@extends('template.master')
@section('content')

<!-- Form Start -->

<section class="container-fluid">
    <div class="registration-form">
        <img src="{{asset('assets/images/backgrounds/event-three-inner-bg.png')}}" alt="">
    </div>
    <div class="container contact-form">
        <form action="submit" class="">
            <h2 class="text-center section-title__title">Pharmasist Registration</h2>
            <h5 class="mb-3 text-center">Personal Information</h5>
            <div class="row">
                <div class="input-field col-lg-12 col-md-12">
                    <input type="text" name="name" required>
                    <label>Full Name</label>
                </div>
            </div>
            <div class="row">
                <input name="profession" type="hidden" value="pharmasist" required>
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

    </div>
</section>
<!-- Form End-->

@endsection