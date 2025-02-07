@php
	$stripCountries = \extras\plugins\stripe\app\Helpers\StripeTools::getCountries();
	$addrLine2Countries = \extras\plugins\stripe\app\Helpers\StripeTools::countriesWhereAddrLine2IsRequired();
	$zipCodeCountries = \extras\plugins\stripe\app\Helpers\StripeTools::countriesWhereZipCodeIsRequired();
@endphp
<div class="row payment-plugin" id="stripePayment" style="display: none;">
	<div class="col-md-10 col-sm-12 box-center center mt-4 mb-0">
		<div class="row">
			
			<div class="col-xl-12 text-center">
				<img class="img-fluid"
				     src="{{ url('plugins/stripe/images/payment.png') }}"
				     title="{{ trans('stripe::messages.payment_with') }}"
				     alt="{{ trans('stripe::messages.payment_with') }}"
				>
			</div>
			
			<div class="col-xl-12 mt-3">
				<!-- CREDIT CARD FORM -->
				<div class="card card-default credit-card-box">
					
					<div class="card-header">
						<h3 class="panel-title">
							{{ trans('stripe::messages.payment_details') }}
						</h3>
					</div>
					
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="mb-3 form-field-box">
									<label class="col-form-label" for="stripeCardNumber">
										{{ trans('stripe::messages.Card Number') }}
									</label>
									<div class="input-group">
										<input
												type="tel"
												class="form-control"
												name="stripeCardNumber"
												placeholder="{{ trans('stripe::messages.Valid Card Number') }}"
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
											<label class="col-form-label" for="stripeCardExpiry">
												{!! trans('stripe::messages.Expiration Date') !!}
											</label>
											<input
													type="tel"
													class="form-control"
													name="stripeCardExpiry"
													placeholder="{{ trans('stripe::messages.MM / YY') }}"
													autocomplete="cc-exp"
													required
											/>
										</div>
									</div>
									<div class="col-md-6 float-end">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeCardCVC">
												{{ trans('stripe::messages.CV Code') }}
											</label>
											<input
													type="tel"
													class="form-control"
													name="stripeCardCVC"
													placeholder="{{ trans('stripe::messages.CVC') }}"
													autocomplete="cc-csc"
													required
											/>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div id="stripePaymentErrors" style="display:none;">
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
							{{ trans('stripe::messages.card_holder_information') }}
						</h3>
					</div>
					
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeCardFirstName">
												{{ trans('stripe::messages.first_name') }}
											</label>
											<input
													type="text"
													class="form-control"
													name="stripeCardFirstName"
													placeholder="{{ trans('stripe::messages.first_name_hint') }}"
													required
											/>
										</div>
									</div>
									<div class="col-md-6 float-end">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeCardLastName">
												{{ trans('stripe::messages.last_name') }}
											</label>
											<input
													type="text"
													class="form-control"
													name="stripeCardLastName"
													placeholder="{{ trans('stripe::messages.last_name_hint') }}"
													required
											/>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="mb-3 form-field-box">
									<label class="col-form-label" for="stripeBillingAddress1">
										{{ trans('stripe::messages.address_1') }}
									</label>
									<input
											type="text"
											class="form-control"
											name="stripeBillingAddress1"
											placeholder="{{ trans('stripe::messages.address_1_hint') }}"
											required
									/>
								</div>
							</div>
							<div class="col-xl-12 d-none" id="address2Box">
								<div class="mb-3 form-field-box">
									<label class="col-form-label" for="stripeBillingAddress2">
										{{ trans('stripe::messages.address_2') }}
									</label>
									<input
											type="text"
											class="form-control"
											name="stripeBillingAddress2"
											placeholder="{{ trans('stripe::messages.address_2_hint') }}"
									/>
								</div>
							</div>
							<div class="col-12">
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeBillingCity">
												{{ trans('stripe::messages.city') }}
											</label>
											<input
													type="text"
													class="form-control"
													name="stripeBillingCity"
													placeholder="{{ trans('stripe::messages.city_hint') }}"
													required
											/>
										</div>
									</div>
									<div class="col-md-6 d-none" id="stateBox">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeBillingState">
												{{ trans('stripe::messages.state') }}
											</label>
											<input
													type="text"
													class="form-control"
													name="stripeBillingState"
													placeholder="{{ trans('stripe::messages.state_hint') }}"
											/>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="row">
									<div class="col-md-6 d-none" id="zipCodeBox">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeBillingZipCode">
												{{ trans('stripe::messages.zip_code') }}
											</label>
											<input
													type="text"
													class="form-control"
													name="stripeBillingZipCode"
													placeholder="{{ trans('stripe::messages.zip_code_hint') }}"
											/>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3 form-field-box">
											<label class="col-form-label" for="stripeBillingCountry">
												{{ trans('stripe::messages.country') }}
											</label>
											<select id="stripeBillingCountry"
											        name="stripeBillingCountry"
											        class="form-control large-data-selecter"
											>
												<option value="">{{ t('select_a_country') }}</option>
												@if ($stripCountries->count() > 0)
													@foreach ($stripCountries as $country)
														<option value="{{ $country->code }}"
																{{ ($country->code == config('country.code')) ? ' selected="selected"' : '' }}
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
	<script src="https://js.stripe.com/v3/"></script>
	<script>
		onDocumentReady((event) => {
			const params = {hasForm: true, hasLocalAction: false};
			
			loadPaymentGateway('stripe', params, (selectedPackage, packagePrice) => {
				if (packagePrice <= 0) {
					return false;
				}
				
				if (!ccFormValidationForStripe()) {
					return false;
				}
				
				payWithStripe();
				
				return false;
			});
			
			/* Apply Country change Actions */
			let stripeBillingCountryEl = $('#stripeBillingCountry');
			stripeApplyCountryChangeActions(stripeBillingCountryEl.val());
			stripeBillingCountryEl.on('change', function () {
				stripeApplyCountryChangeActions($(this).val());
			});
			
			/* Fancy restrictive input formatting via jQuery.payment library */
			$('input[name=stripeCardNumber]').payment('formatCardNumber');
			$('input[name=stripeCardCVC]').payment('formatCardCVC');
			$('input[name=stripeCardExpiry]').payment('formatCardExpiry');
		});
		
		/* Pay with the Payment Method */
		function payWithStripe() {
			let submitBtn = document.getElementById('payableFormSubmitButton');
			
			/* Visual feedback */
			submitBtn.disabled = true;
			submitBtn.innerHTML = '{{ trans('stripe::messages.Processing') }} <i class="fa-solid fa-spinner fa-pulse"></i>';
			
			$('#payableForm').submit();
		}
		
		function ccFormValidationForStripe() {
			let formEl = $('#payableForm');
			
			/* Form validation */
			jQuery.validator.addMethod('stripeCardExpiry', function(value, element) {
				/* Regular expression to match Credit Card expiration date */
				var reg = new RegExp('^(0[1-9]|1[0-2])\\s?\/\\s?([0-9]{4}|[0-9]{2})$');
				return this.optional(element) || reg.test(value);
			}, "{{ trans('stripe::messages.Invalid expiration date') }}");
			
			jQuery.validator.addMethod('stripeCardCVC', function(value, element) {
				/* Regular expression matching a 3 or 4 digit CVC (or CVV) of a Credit Card */
				var reg = new RegExp('^[0-9]{3,4}$');
				return this.optional(element) || reg.test(value);
			}, "{{ trans('stripe::messages.Invalid CVC') }}");
			
			let validator = formEl.validate({
				rules: {
					stripeCardNumber: {
						required: true
					},
					stripeCardExpiry: {
						required: true,
						stripeCardExpiry: true
					},
					stripeCardCVC: {
						required: true,
						stripeCardCVC: true
					},
					stripeCardFirstName: {
						required: true
					},
					stripeCardLastName: {
						required: true
					},
					stripeBillingAddress1: {
						required: true
					},
					stripeBillingCity: {
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
					formEl.find('[name=stripeCardNumber]').hasClass('is-valid')
					&& formEl.find('[name=stripeCardExpiry]').hasClass('is-valid')
					&& formEl.find('[name=stripeCardCVC]').val().length > 1
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
		
		function stripeApplyCountryChangeActions(countryCode) {
			let formEl = $('#payableForm');
			
			if (isAddress2IsRequired(countryCode)) {
				formEl.find('#address2Box').removeClass('d-none');
				formEl.find('[name=stripeBillingAddress2]').prop('required', true);
			} else {
				formEl.find('[name=stripeBillingAddress2]').prop('required', false);
				formEl.find('#address2Box').addClass('d-none');
			}
			
			if (isZipCodeIsRequired(countryCode)) {
				formEl.find('#stateBox').removeClass('d-none');
				formEl.find('[name=stripeBillingState]').prop('required', true);
				
				formEl.find('#zipCodeBox').removeClass('d-none');
				formEl.find('[name=stripeBillingZipCode]').prop('required', true);
			} else {
				formEl.find('[name=stripeBillingState]').prop('required', false);
				formEl.find('#stateBox').addClass('d-none');
				
				formEl.find('[name=stripeBillingZipCode]').prop('required', false);
				formEl.find('#zipCodeBox').addClass('d-none');
			}
		}
		
		function isAddress2IsRequired(countryCode) {
			if (countryCode === '') {
				return false;
			}
			
			let addrLine2Countries = {!! $addrLine2Countries !!};
			
			return addrLine2Countries.includes(countryCode);
		}
		
		function isZipCodeIsRequired(countryCode) {
			if (countryCode === '') {
				return false;
			}
			
			let zipCodeCountries = {!! $zipCodeCountries !!};
			
			return zipCodeCountries.includes(countryCode);
		}
	
	</script>
@endsection
