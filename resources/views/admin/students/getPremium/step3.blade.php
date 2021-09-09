<div class="container px-0">
    <div class="card card-outline card-warning d-none" id="step3">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="invoice p-3 mb-3">
                        <div class="card-body box-profile">
                            <h4 class="text-center">{{ __('student.getPremium.step3.header') }}</h4>
                            <p class="text-center">{{ __('student.getPremium.step3.start_date') }}: <strong id="start_date"></strong></p>
                            <p class="text-center">{{ __('student.getPremium.step3.next_renewal_date') }}: <strong id="next_renewal_date"></strong></p>
                            <p class="text-center">{{ __('student.getPremium.step3.amount') }}: <strong id="amount"></strong></p>
                            <div class="text-center">
                                <img class="img-circle" src="{{ asset('images/success-04.png') }}" alt="User profile picture" style="width: 50%">
                            </div>
                            <h3 class="profile-username text-center">{{ __('student.getTrial.step3.back') }} <a href="{{route('student-dashboard')}}">{{ __('student.getTrial.step3.home') }}</a></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
