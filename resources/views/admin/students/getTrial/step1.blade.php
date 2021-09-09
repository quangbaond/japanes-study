<div class="container px-0">
    <div class="card card-outline card-warning" id="step1">
        <div class="card-header float-right">
            <h3 class="font-weight-bold">{{ __('student.step2.header_title_7_days_free_trial') }}</h3>
            <span class="pl-4">{{ __('student.step2.header_content_7_days_free_trial') }}</span>
        </div>
        <div class="row card-body">
            <div class="col-sm-6 pt-0">
                <div class="text-center card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('student.step2.premium_membership.title') }}</h3>
                    </div>
                </div>
                <div class="tab-pane">
                    <p class="font-weight-bold">⭐{{ __('student.step2.premium_membership.content1') }}</p>
                    <p class="pl-4 ml-2">{{ __('student.step2.premium_membership.information1') }}</p>
                </div>
                <div class="tab-pane">
                    <p class="font-weight-bold">⭐{{ __('student.step2.premium_membership.content2') }}</p>
                    <p class="pl-4 ml-2">{{ __('student.step2.premium_membership.information2') }}</p>
                </div>
                <div class="tab-pane">
                    <p class="font-weight-bold">⭐{{ __('student.step2.premium_membership.content3') }}</p>
                    <p class="pl-4 ml-2">{{ __('student.step2.premium_membership.information3') }}</p>
                </div>
            </div>
            <div class="col-sm-6 pt-0">
                <div class="text-center card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('student.step2.premium_plan.title') }}</h3>
                    </div>
                </div>
                <div class="tab-pane pl-3">
                    <div class="">
                        <p><span class="text-danger">*</span><span id="error_plans">{{ __('student.step2.premium_plan.please_choose') }}</span></p>
                        <div class="form-group clearfix">
                            @foreach($plans as $plan)
                                <div class="icheck-primary text-overflow-ellipsis">
                                    <input
                                        type="radio"
                                        name="plans"
                                        data-cost="{{ number_format($plan->cost) }}"
                                        data-interval="{{ $plan->interval }}"
                                        data-interval-count="{{ $plan->interval_count }}"
                                        value="{{ $plan->id }}"
                                        data-value="{{ $plan->name }}"
                                        id="checkboxPrimary{{ $plan->id }}"
                                    >
                                    <label for="checkboxPrimary{{ $plan->id }}" class="error_plan_label">{{ $plan->name }} ({{ number_format($plan->cost) }} VND / {{ $plan->interval_count }} {{ $plan->interval }})</label>
                                </div>
                            @endforeach
                        </div>
                        <p class="pl-4 ml-1">{{ __('student.step2.premium_plan.1_lesson') }}</p>
                        <p>⭐{{ __('student.step2.premium_plan.content1') }}</p>
                        <p>⭐{{ __('student.step2.premium_plan.content2') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('student-dashboard') }}"><buton type="button" class="btn btn-default btn-flat float-left">{{ __('button.cancel') }}</buton></a>
            <buton type="button" class="btn btn-primary btn-flat float-right ml-2" data-toggle="modal" data-target="#modal-lg" id="goToStep2">
                {{ __('button.next') }}
            </buton>
        </div>
    </div>
</div>

<!-- modal-content -->
<div class="modal fade" id="modal-confirm-plans">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">{{ __('student.step2.popup.register_confirm') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ml-4">
                <p>{{ __('student.step2.popup.you_have') }} <strong id="name_plan_choice"></strong> {{ __('student.step2.popup.premium_plan') }} . {{ __('student.step2.popup.you_can') }}</p>
                <p>{{ __('student.step2.popup.deadline') }} <strong id="date_deadline"></strong></p>
                <p>{{ __('student.step2.popup.content1') }}</p>
                <div class="icheck-primary">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        {{ __('student.step2.popup.i_agree') }}
                    </label>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ __('button.close') }}</button>
                <button type="button" id ="btnGoToStep2" class="btn btn-primary btn-flat" disabled>{{ __('student.step2.popup.button_go_to_step3') }}</button>
            </div>
        </div>
    </div>
</div>

