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
            
            <!-- ... -->
            
        </div>
        
    </div>
</div>

@section('after_scripts')
    @parent
    <script>
        onDocumentReady((event) => {
            const params = {hasForm: false, hasLocalAction: false};
            
            loadPaymentGateway('twocheckout', params);
        });
    </script>
@endsection
