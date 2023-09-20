<form id="main-form" class="w-px-500 p-3 p-md-3" action="{{ route('submit') }}" method="post">
    @csrf
    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Company Symbol</label>
        <div class="col-sm-9">
            <input
                type="text"
                id="company-symbol"
                class="form-control @error('company_symbol') is-invalid @enderror"
                name="company_symbol"
                placeholder="Company Symbol"
                value="{{ old('company_symbol') }}"
            >
            @error('company_symbol')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Start Date</label>
        <div class="col-sm-9">
            <input
                type="text"
                id="start-date"
                class="form-control datepicker @error('start_date') is-invalid @enderror"
                name="start_date"
                placeholder="Start Date"
                value="{{ old('start_date') }}"
            >
            @error('start_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">End Date</label>
        <div class="col-sm-9">
            <input
                type="text"
                id="end-date"
                class="form-control datepicker @error('end_date') is-invalid @enderror"
                name="end_date"
                placeholder="End Date"
                value="{{ old('end_date') }}"
            >
            @error('end_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <input
                type="email"
                id="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email"
                placeholder="Email"
                value="{{ old('email') }}"
            >
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
            <button id="submit" type="submit" class="btn btn-success btn-block text-white">Submit</button>
        </div>
    </div>
</form>
