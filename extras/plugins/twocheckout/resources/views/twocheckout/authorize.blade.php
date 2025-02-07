@php
    $coCountries = \extras\plugins\twocheckout\app\Helpers\CoTools::getCountries();
	$addrLine2Countries = \extras\plugins\twocheckout\app\Helpers\CoTools::countriesWhereAddrLine2IsRequired();
	$zipCodeCountries = \extras\plugins\twocheckout\app\Helpers\CoTools::countriesWhereZipCodeIsRequired();
@endphp
<div class="row payment-plugin" id="twocheckoutPayment" style="display: none;">
    <div class="col-md-10 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">
            
            <div class="col-xl-12 text-center">
                <img class="img-fluid"
                     src="{{ url('plugins/twocheckout/images/payment.png') }}"
                     title="{{ trans('twocheckout::messages.payment_with') }}"
                     alt="{{ trans('twocheckout::messages.payment_with') }}"
                >
            </div>
            
            <div class="col-xl-12 mt-3">
                <!-- CREDIT CARD FORM -->
                <div class="card card-default credit-card-box">
                
                    <div class="card-header">
                        <h3 class="panel-title">
                            {{ trans('twocheckout::messages.payment_details') }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3 form-field-box">
                                    <label class="col-form-label" for="twocheckoutCardNumber">
                                        {{ trans('twocheckout::messages.Card Number') }}
                                    </label>
                                    <div class="input-group">
                                        <input
                                                type="tel"
                                                class="form-control"
                                                name="twocheckoutCardNumber"
                                                placeholder="{{ trans('twocheckout::messages.Valid Card Number') }}"
                                                autocomplete="cc-number"
                                                required
                                        />
                                        <span class="input-group-text">
                                            <i class="fa-regular fa-credit-card"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutCardExpiry">
                                                {!! trans('twocheckout::messages.Expiration Date') !!}
                                            </label>
                                            <input
                                                    type="tel"
                                                    class="form-control"
                                                    name="twocheckoutCardExpiry"
                                                    placeholder="{{ trans('twocheckout::messages.MM / YY') }}"
                                                    autocomplete="cc-exp"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6 float-end">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutCardCVC">
                                                {{ trans('twocheckout::messages.CV Code') }}
                                            </label>
                                            <input
                                                    type="tel"
                                                    class="form-control"
                                                    name="twocheckoutCardCVC"
                                                    placeholder="{{ trans('twocheckout::messages.CVC') }}"
                                                    autocomplete="cc-csc"
                                                    required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="twocheckoutPaymentErrors" style="display:none;">
                            <div class="col-xs-12">
                                <p class="payment-errors"></p>
                            </div>
                        </div>
                    </div>
            
                </div>
                <!-- /CREDIT CARD FORM -->
            </div>
    
            <div class="col-xl-12 mt-3">
                <!-- CARD HOLDER INFORMATION -->
                <div class="card card-default credit-card-box">
            
                    <div class="card-header">
                        <h3 class="panel-title">
                            {{ trans('twocheckout::messages.card_holder_information') }}
                        </h3>
                    </div>
            
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutCardFirstName">
                                                {{ trans('twocheckout::messages.first_name') }}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="twocheckoutCardFirstName"
                                                    placeholder="{{ trans('twocheckout::messages.first_name_hint') }}"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6 float-end">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutCardLastName">
                                                {{ trans('twocheckout::messages.last_name') }}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="twocheckoutCardLastName"
                                                    placeholder="{{ trans('twocheckout::messages.last_name_hint') }}"
                                                    required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3 form-field-box">
                                    <label class="col-form-label" for="twocheckoutBillingAddress1">
                                        {{ trans('twocheckout::messages.address_1') }}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="twocheckoutBillingAddress1"
                                            placeholder="{{ trans('twocheckout::messages.address_1_hint') }}"
                                            required
                                    />
                                </div>
                            </div>
                            <div class="col-xl-12 d-none" id="twocheckoutAddress2Box">
                                <div class="mb-3 form-field-box">
                                    <label class="col-form-label" for="twocheckoutBillingAddress2">
                                        {{ trans('twocheckout::messages.address_2') }}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="twocheckoutBillingAddress2"
                                            placeholder="{{ trans('twocheckout::messages.address_2_hint') }}"
                                    />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutBillingCity">
                                                {{ trans('twocheckout::messages.city') }}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="twocheckoutBillingCity"
                                                    placeholder="{{ trans('twocheckout::messages.city_hint') }}"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-none" id="twocheckoutStateBox">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutBillingState">
                                                {{ trans('twocheckout::messages.state') }}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="twocheckoutBillingState"
                                                    placeholder="{{ trans('twocheckout::messages.state_hint') }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 d-none" id="twocheckoutZipCodeBox">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutBillingZipCode">
                                                {{ trans('twocheckout::messages.zip_code') }}
                                            </label>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    name="twocheckoutBillingZipCode"
                                                    placeholder="{{ trans('twocheckout::messages.zip_code_hint') }}"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 form-field-box">
                                            <label class="col-form-label" for="twocheckoutBillingCountry">
                                                {{ trans('twocheckout::messages.country') }}
                                            </label>
                                            <select id="twocheckoutBillingCountry"
                                                    name="twocheckoutBillingCountry"
                                                    class="form-control large-data-selecter"
                                            >
                                                <option value="">{{ t('select_a_country') }}</option>
                                                @if ($coCountries->count() > 0)
                                                    @foreach ($coCountries as $country)
                                                        @continue(!isset($country->iso3))
                                                        <option value="{{ $country->iso3 }}"
                                                            {{ ($country->iso3 == config('country.iso3')) ? ' selected="selected"' : '' }}
                                                        >{{ $country->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>
                <!-- /CARD HOLDER INFORMATION -->
            </div>
            
        </div>
        
    </div>
</div>

@section('after_scripts')
    @parent
    <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
    <script>
        onDocumentReady((event) => {
            const params = {hasForm: true, hasLocalAction: true};
            
            loadPaymentGateway('twocheckout', params, (selectedPackage, packagePrice) => {
                if (packagePrice <= 0) {
                    return false;
                }
                
                if (!ccFormValidationForCheckout()) {
                    return false;
                }
                
                /* Call the token request function */
                payWithCheckout();
                
                return false;
            });
            
            /* Apply Country change Actions */
            let twocheckoutBillingCountryEl = $('#twocheckoutBillingCountry');
            twocheckoutApplyCountryChangeActions(twocheckoutBillingCountryEl.val());
            twocheckoutBillingCountryEl.on('change', function () {
                twocheckoutApplyCountryChangeActions($(this).val());
            });
            
            /* Fancy restrictive input formatting via jQuery.payment library */
            $('input[name=twocheckoutCardNumber]').payment('formatCardNumber');
            $('input[name=twocheckoutCardCVC]').payment('formatCardCVC');
            $('input[name=twocheckoutCardExpiry]').payment('formatCardExpiry');
            
            {{-- Pull in the public encryption key for our environment --}}
            {{-- https://www.2checkout.com/documentation/payment-api/tutorial --}}
            {{-- https://www.2checkout.com/documentation/payment-api/create-token --}}
            TCO.loadPubKey('{{ config('payment.twocheckout.mode') }}');
        });
        
        /* Pay with the Payment Method */
        function payWithCheckout() {
            let formEl = $('#payableForm');
            
            /* Visual feedback */
            formEl.find('#payableFormSubmitButton')
                    .html('{{ trans('twocheckout::messages.Validating') }} <i class="fa-solid fa-spinner fa-pulse"></i>')
                    .prop('disabled', true);
            
            /* Called when token created successfully */
            let successCallback = function(data)
            {
                /* Visual feedback */
                formEl.find('#payableFormSubmitButton')
                        .html('{{ trans('twocheckout::messages.Processing') }} <i class="fa-solid fa-spinner fa-pulse"></i>');
                
                /* Hide Stripe errors on the form */
                formEl.find('#twocheckoutPaymentErrors').hide();
                formEl.find('#twocheckoutPaymentErrors').find('.payment-errors').text('');
                
                /* Set the token as the value for the token input */
                let twocheckoutToken = data.response.token.token;
                formEl.append($('<input type="hidden" name="twocheckoutToken" />').val(twocheckoutToken));
                
                /*
                 * IMPORTANT: Here we call `submit()` on the form element directly
                 * instead of using jQuery to prevent and infinite token request loop.
                 */
                formEl.submit();
            };
            
            /* Called when token creation fails */
            let errorCallback = function(data) {
                if (data.errorCode === 200) {
                    /* Call the 2Checkout's token request function */
                    tokenRequest();
                } else {
                    /* Visual feedback */
                    formEl.find('#payableFormSubmitButton')
                            .html('{{ trans('twocheckout::messages.Try again') }}')
                            .prop('disabled', false);
        
                    /* Show errors on the form */
                    formEl.find('#twocheckoutPaymentErrors').find('.payment-errors').text(data.errorMsg);
                    formEl.find('#twocheckoutPaymentErrors').show();
                }
            };
            
            /* Setup token request arguments */
            let twocheckoutCardExpiry = $('input[name=twocheckoutCardExpiry]').val().split('/');
            let args = {
                sellerId: '{{ config('payment.twocheckout.sellerId') }}',
                publishableKey: '{{ config('payment.twocheckout.publishableKey') }}',
                ccNo: $('input[name=twocheckoutCardNumber]').val().replace(/\s/g,''),
                cvv: $('input[name=twocheckoutCardCVC]').val(),
                expMonth: (0 in twocheckoutCardExpiry) ? twocheckoutCardExpiry[0].replace(/\s/g,'') : '',
                expYear: (1 in twocheckoutCardExpiry) ? twocheckoutCardExpiry[1].replace(/\s/g,'') : ''
            };
            
            {{-- Make the token request --}}
            TCO.requestToken(successCallback, errorCallback, args);
        }
        
        function ccFormValidationForCheckout() {
            let formEl = $('#payableForm');
            
            /* Form validation */
            jQuery.validator.addMethod('twocheckoutCardExpiry', function(value, element) {
                /* Regular expression to match Credit Card expiration date */
                let reg = new RegExp('^(0[1-9]|1[0-2])\\s?\/\\s?([0-9]{4}|[0-9]{2})$');
                return this.optional(element) || reg.test(value);
            }, "{{ trans('twocheckout::messages.Invalid expiration date') }}");
            
            jQuery.validator.addMethod('twocheckoutCardCVC', function(value, element) {
                /* Regular expression matching a 3 or 4 digit CVC (or CVV) of a Credit Card */
                let reg = new RegExp('^[0-9]{3,4}$');
                return this.optional(element) || reg.test(value);
            }, "{{ trans('twocheckout::messages.Invalid CVC') }}");
            
            let validator = formEl.validate({
                rules: {
                    twocheckoutCardNumber: {
                        required: true
                    },
                    twocheckoutCardExpiry: {
                        required: true,
                        twocheckoutCardExpiry: true
                    },
                    twocheckoutCardCVC: {
                        required: true,
                        twocheckoutCardCVC: true
                    },
                    twocheckoutCardFirstName: {
                        required: true
                    },
                    twocheckoutCardLastName: {
                        required: true
                    },
                    twocheckoutBillingAddress1: {
                        required: true
                    },
                    twocheckoutBillingCity: {
                        required: true
                    }
                },
                highlight: function(element) {
                    $(element).removeClass('is-valid').addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-field-box').append(error);
                }
            });
    
            let paymentFormReady = function() {
                return (
                    formEl.find('[name=twocheckoutCardNumber]').hasClass('is-valid')
                    && formEl.find('[name=twocheckoutCardExpiry]').hasClass('is-valid')
                    && formEl.find('[name=twocheckoutCardCVC]').val().length > 1
                );
            };
            
            formEl.find('#payableFormSubmitButton').prop('disabled', true);
            let readyInterval = setInterval(function() {
                if (paymentFormReady()) {
                    formEl.find('#payableFormSubmitButton').prop('disabled', false);
                    clearInterval(readyInterval);
                }
            }, 250);
            
            /* Abort if invalid form data */
            return validator.form();
        }
        
        function twocheckoutApplyCountryChangeActions(countryIso3Code) {
            let formEl = $('#payableForm');
            
            if (twocheckoutDoesAddress2IsRequired(countryIso3Code)) {
                formEl.find('#twocheckoutAddress2Box').removeClass('d-none');
                formEl.find('[name=twocheckoutBillingAddress2]').prop('required', true);
            } else {
                formEl.find('[name=twocheckoutBillingAddress2]').prop('required', false);
                formEl.find('#twocheckoutAddress2Box').addClass('d-none');
            }
            
            if (twocheckoutDoesZipCodeIsRequired(countryIso3Code)) {
                formEl.find('#twocheckoutStateBox').removeClass('d-none');
                formEl.find('[name=twocheckoutBillingState]').prop('required', true);
                
                formEl.find('#twocheckoutZipCodeBox').removeClass('d-none');
                formEl.find('[name=twocheckoutBillingZipCode]').prop('required', true);
            } else {
                formEl.find('[name=twocheckoutBillingState]').prop('required', false);
                formEl.find('#twocheckoutStateBox').addClass('d-none');
                
                formEl.find('[name=twocheckoutBillingZipCode]').prop('required', false);
                formEl.find('#twocheckoutZipCodeBox').addClass('d-none');
            }
        }
        
        function twocheckoutDoesAddress2IsRequired(countryIso3Code) {
            if (countryIso3Code === '') {
                return false;
            }
            
            let addrLine2Countries = {!! $addrLine2Countries !!};
            
            return addrLine2Countries.includes(countryIso3Code);
        }
        
        function twocheckoutDoesZipCodeIsRequired(countryIso3Code) {
            if (countryIso3Code === '') {
                return false;
            }
            
            let zipCodeCountries = {!! $zipCodeCountries !!};
            
            return zipCodeCountries.includes(countryIso3Code);
        }
        
    </script>
@endsection
