@extends('layouts.app')

@push('script')
    <script>
        $( function() {
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: new Date(),
                onSelect: function(selected) {
                    if ('start-date' === this.id) {
                        $("#end-date").datepicker('option', 'minDate', selected);
                    }
                }
            });

            $('#submit').click(function () {
                let formValid = true;

                let companySymbolObj = $('#company-symbol');
                let companySymbolVal = companySymbolObj.val();

                let startDateObj = $('#start-date');
                let startDateVal = startDateObj.val();

                let endDateObj = $('#end-date');
                let endDateVal = endDateObj.val();

                let emailObj = $('#email');
                let email = emailObj.val();

                $('#main-form .invalid-feedback').remove();

                emailObj.removeClass('is-invalid');
                startDateObj.removeClass('is-invalid');
                endDateObj.removeClass('is-invalid');
                companySymbolObj.removeClass('is-invalid');

                if ('' === companySymbolVal) {
                    companySymbolObj.addClass('is-invalid').after(buildErrorBlock('Company symbol is required'));
                    formValid = false;
                }

                if ('' === email) {
                    emailObj.addClass('is-invalid').after(buildErrorBlock('Email is empty'));
                    formValid = false;
                } else if (false === isEmail(email)) {
                    emailObj.addClass('is-invalid').after(buildErrorBlock('Email is invalid'));
                    formValid = false;
                }

                let startDateValParsed = Date.parse(startDateVal);
                let endDateValParsed = Date.parse(endDateVal);

                if ('' === startDateVal) {
                    startDateObj.addClass('is-invalid').after(buildErrorBlock('Start date is required'));
                    formValid = false;
                }
                else if (!startDateValParsed) {
                    startDateObj.addClass('is-invalid').after(buildErrorBlock('Start date format is wrond'));
                    formValid = false;
                }

                if ('' === endDateVal) {
                    endDateObj.addClass('is-invalid').after(buildErrorBlock('End date is required'));
                    formValid = false;
                } else if (!endDateValParsed) {
                    endDateObj.addClass('is-invalid').after(buildErrorBlock('End date format is wrong'));
                    formValid = false;
                }

                let todayParsed = Date.parse(new Date());

                if (startDateValParsed && startDateValParsed > todayParsed) {
                    startDateObj.addClass('is-invalid').after(buildErrorBlock('The date must be less or equal than the current date'));
                    formValid = false;
                }

                if (endDateValParsed && endDateValParsed > todayParsed) {
                    endDateObj.addClass('is-invalid').after(buildErrorBlock('The date must be less or equal than the current date'));
                    formValid = false;
                }

                if (startDateValParsed && endDateValParsed && startDateValParsed > endDateValParsed) {
                    startDateObj.addClass('is-invalid').after(buildErrorBlock('The date must be less or equal than the End date'));
                    endDateObj.addClass('is-invalid').after(buildErrorBlock('The date must be greater or equal than the Start date'));
                    formValid = false;
                }

                if (false === formValid) {
                    return false;
                }
            });

            function isEmail(email) {
                const regEx = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                if (!regEx.test(email)) {
                    return false;
                }

                return true;
            }

            function buildErrorBlock(message) {
                if ('' === message) {
                    return false;
                }

                return '<span class="invalid-feedback" role="alert"><strong>' + message + '</strong></span>';
            }

            $('#company-symbol').autocomplete({
                source: {!! $availableCompanySymbols !!},
                change: function( event, ui ) {
                    if (ui.item === null || !ui.item) {
                        $(this).val('');
                    }
                },
                minLength: 2
            });
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span>Please, fill in the form below</span>
                    </div>
                    <div class="card-body">
                        @include('forms.main_form')
                    </div>
                </div>
            </div>
        </div>

        @if(isset($historicalData))
            @if($historicalData)
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8 text-center">
                        <span class="fs-3">Historical Data</span>
                    </div>
                </div>

                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Open</th>
                                <th scope="col">High</th>
                                <th scope="col">Low</th>
                                <th scope="col">Close</th>
                                <th scope="col">Volume</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($historicalData as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $item['date'] ?? '' }}</td>
                                    <td>{{ $item['open'] ?? ''}}</td>
                                    <td>{{ $item['high'] ?? ''}}</td>
                                    <td>{{ $item['low'] ?? ''}}</td>
                                    <td>{{ $item['close'] ?? ''}}</td>
                                    <td>{{ $item['volume'] ?? ''}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row justify-content-center mt-3">
                    <div class="col-md-8 text-center">
                        <span class="fs-3">Chart</span>
                    </div>
                </div>

                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <canvas id="historicalDataChart"></canvas>
                    </div>
                </div>
                @push('script')
                    <script>
                        $( function() {
                            const ctx = document.getElementById('historicalDataChart');

                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: {!! $chartDataDates !!},
                                    datasets: [
                                        {
                                            label: 'Open',
                                            data: {!! $chartDataOpenPrices !!},
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Close',
                                            data: {!! $chartDataClosePrices !!},
                                            borderWidth: 1
                                        }
                                    ]
                                }
                            });
                        });
                    </script>
                @endpush
            @else
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8 text-center">
                        <span class="fs-3">Historical Data is empty for the data provided</span>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
