<div class="tab-pane fade" id="cash_tab" role="tabpanel">
    <form action="{{ route('admin.update-cash-payment') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="">{{ __('Informations sur le paiement comptant') }}</label>
            <textarea name="cash_information" cols="30" rows="10" class="text-area-5 form-control">{{ $basic_payment->cash_information }}</textarea>
        </div>

        <div class="form-group">
            <label>{{ __('Nouvelle Image') }} <code>({{ __('Recommandé') }} : 210X100 PX)</code></label>
            <div id="image-preview-cash" class="image-preview">
                <label for="image-upload-cash" id="image-label-cash">{{ __('Image') }}</label>
                <input type="file" name="cash_image" id="image-upload-cash">
            </div>
        </div>

        <div class="form-group">
            <label class="d-flex align-items-center">
                <input type="hidden" value="inactive" name="cash_status" class="custom-switch-input">
                <input type="checkbox" value="active" name="cash_status" class="custom-switch-input"
                    {{ $basic_payment?->cash_status == 'active' ? 'checked' : '' }}>
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">{{ __('Statut') }}</span>
            </label>
        </div>

        <button class="btn btn-primary">{{ __('Mettre à jour') }}</button>
    </form>
</div>
