<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Doctor & Worker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Register Doctor & Worker</h3>

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

                <form id="registerForm" action="{{ route('form.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <h5 class="text-center mb-4">Personal Information</h5>

                        <div class="col-md-6 mb-3">
                            <label for="profession" class="form-label">Profession</label>
                            <select class="form-select" id="profession" name="designation" value="{{ old('designation') }}">
                                <option value="">Select Profession</option>
                                <option value="doctor" {{ old('designation') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="worker" {{ old('designation') == 'worker' ? 'selected' : '' }}>Worker</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        </div>

                        
                            {{-- <label for="name" class="form-label">Account Request</label> --}}
                            <input type="hidden" name="account_request" value="1">
                        

                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sex" class="form-label">Sex</label>
                            <input type="text" class="form-control" id="sex" name="sex" value="{{ old('sex') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="consent_file" class="form-label">Consent File (Online Signature)</label>
                            <input type="file" class="form-control" id="consent_file" name="consent_file" accept="image/*">
                        </div>
                        

                        <div class="col-md-6 mb-3">
                            <label for="village" class="form-label">Village</label>
                            <input type="text" class="form-control" id="village" name="village" value="{{ old('village') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="district" class="form-label">District</label>
                            <input type="text" class="form-control" id="district" name="district" value="{{ old('district') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="buildingNo" class="form-label">Building Number</label>
                            <input type="text" class="form-control" id="buildingNo" name="buildingNo" value="{{ old('buildingNo') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
