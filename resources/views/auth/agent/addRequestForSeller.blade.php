@extends('auth.theme.dashboard')
@section('title') {{ __('pages.add_request_for_seller') }} @endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.add_request_for_seller') }}</h6>
                    <p class="text-sm text-muted">{{ __('pages.request_for_seller_hint') }}</p>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('agent.storeRequestForSeller') }}" method="POST" enctype="multipart/form-data" id="request-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('pages.select_seller') }}*</label>
                                <select name="seller_id" class="form-control" required>
                                    <option value="" selected disabled hidden>{{ __('pages.select_seller') }}</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->name }} ({{ $seller->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('seller_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('pages.product_name') }}*</label>
                                <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
                                @error('product_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    {{ __('pages.product_url') }}
                                    <small class="text-muted">({{ __('pages.or_image_required') }})</small>
                                </label>
                                <input type="url" id="product_url" name="product_url" class="form-control" placeholder="https://..." value="{{ old('product_url') }}">
                                @error('product_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    {{ __('pages.product_image') }}
                                    <small class="text-muted">({{ __('pages.or_url_required') }})</small>
                                </label>
                                <input type="file" id="product_image" name="product_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                @error('product_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-2" id="media-error" style="display:none;">
                                <div class="alert alert-danger py-2">{{ __('pages.url_or_image_required') }}</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('pages.category') }}</label>
                                <select name="category" class="form-control">
                                    <option value="none" selected disabled hidden>{{ __('pages.select_category') }}</option>
                                    <option value="Automobiles & Motorcycles"  {{ old('category') == 'Automobiles & Motorcycles'  ? 'selected' : '' }}>{{ __('pages.cat_automobiles') }}</option>
                                    <option value="Bag & Shoes"                {{ old('category') == 'Bag & Shoes'                ? 'selected' : '' }}>{{ __('pages.cat_bags_shoes') }}</option>
                                    <option value="Computer & Office"          {{ old('category') == 'Computer & Office'          ? 'selected' : '' }}>{{ __('pages.cat_computer') }}</option>
                                    <option value="Health & Beauty, Hair"      {{ old('category') == 'Health & Beauty, Hair'      ? 'selected' : '' }}>{{ __('pages.cat_health') }}</option>
                                    <option value="Home & Garden, Furniture"   {{ old('category') == 'Home & Garden, Furniture'   ? 'selected' : '' }}>{{ __('pages.cat_home_garden') }}</option>
                                    <option value="Home Improvement"           {{ old('category') == 'Home Improvement'           ? 'selected' : '' }}>{{ __('pages.cat_home_improvement') }}</option>
                                    <option value="Jewelry & Watches"          {{ old('category') == 'Jewelry & Watches'          ? 'selected' : '' }}>{{ __('pages.cat_jewelry') }}</option>
                                    <option value="Men's Clothing"             {{ old('category') == "Men's Clothing"             ? 'selected' : '' }}>{{ __('pages.cat_mens_clothing') }}</option>
                                    <option value="Other"                      {{ old('category') == 'Other'                      ? 'selected' : '' }}>{{ __('pages.cat_other') }}</option>
                                    <option value="Phones & Accessories"       {{ old('category') == 'Phones & Accessories'       ? 'selected' : '' }}>{{ __('pages.cat_phones') }}</option>
                                    <option value="Sports & Outdoors"          {{ old('category') == 'Sports & Outdoors'          ? 'selected' : '' }}>{{ __('pages.cat_sports') }}</option>
                                    <option value="Toys, Kids & Baby"          {{ old('category') == 'Toys, Kids & Baby'          ? 'selected' : '' }}>{{ __('pages.cat_toys') }}</option>
                                    <option value="Women's Clothing"           {{ old('category') == "Women's Clothing"           ? 'selected' : '' }}>{{ __('pages.cat_womens_clothing') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('pages.quantity') }}*</label>
                                <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 30) }}" required>
                                @error('quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('pages.destination_country') }}*</label>
                                <select name="countryTo" class="form-control" required>
                                    <option selected disabled hidden>{{ __('pages.select_country') }}</option>
                                    @foreach($destinationCountries as $country)
                                        <option value="{{ $country->id }}" {{ old('countryTo') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('countryTo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('pages.sourcing_country') }}*</label>
                                <select name="countryFrom" class="form-control" required>
                                    <option selected disabled hidden>{{ __('pages.select_country') }}</option>
                                    @foreach($sourcingCountries as $country)
                                        <option value="{{ $country->id }}" {{ old('countryFrom') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('countryFrom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('pages.note') }}</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="{{ __('pages.special_request') }}">{{ old('note') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('pages.shipping_method') }}</label>
                                @error('shipping_method')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="air_freight" value="Air freight" {{ old('shipping_method') == 'Air freight' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="air_freight">{{ __('pages.air_freight') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="ocean_freight" value="Ocean freight" {{ old('shipping_method') == 'Ocean freight' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ocean_freight">{{ __('pages.ocean_freight') }}</label>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <a href="{{ route('agent.productRequests') }}" class="btn btn-secondary me-2">{{ __('pages.close') }}</a>
                                <button type="submit" class="btn bg-gradient-success text-white">{{ __('pages.request_product') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('request-form').addEventListener('submit', function(e) {
    var url   = document.getElementById('product_url').value.trim();
    var image = document.getElementById('product_image').files.length;
    var err   = document.getElementById('media-error');
    if (!url && !image) {
        e.preventDefault();
        err.style.display = 'block';
        document.getElementById('product_url').scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        err.style.display = 'none';
    }
});
document.getElementById('product_url').addEventListener('input', function() {
    document.getElementById('media-error').style.display = 'none';
});
document.getElementById('product_image').addEventListener('change', function() {
    document.getElementById('media-error').style.display = 'none';
});
</script>
@endpush
@endsection
