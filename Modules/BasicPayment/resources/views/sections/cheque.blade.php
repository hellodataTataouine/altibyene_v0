<div class="tab-pane fade" id="cheque_tab" role="tabpanel">
    <form action="{{ route('admin.update-cheque-payment') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="">{{ __('Account Information') }}</label>
            <textarea name="cheque_information" id="" cols="30" rows="10" class="text-area-5 form-control">{{ $basic_payment->cheque_information }}</textarea>
        </div>

        <div class="form-group">
            <label>{{ __('New Image') }} <code>({{ __('Recommended') }}: 210X100 PX)</code></label>
            <div id="image-preview-cheque" class="image-preview">
                <label for="image-upload-cheque"
                    id="image-label-bank">{{ __('Image') }}</label>
                <input type="file" name="cheque_image" id="image-upload-cheque">
            </div>

        </div>
        <div class="form-group">
            <label class="d-flex align-items-center">
                <input type="hidden" value="inactive" name="cheque_status" class="custom-switch-input">
                <input type="checkbox" value="active" name="cheque_status" class="custom-switch-input"
                    {{ $basic_payment?->cheque_status == 'active' ? 'checked' : '' }}>
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">{{ __('Status') }}</span>
            </label>
        </div>

        <button class="btn btn-primary">{{ __('Update') }}</button>
    </form>
</div>
