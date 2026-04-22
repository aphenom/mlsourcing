@extends('auth.theme.dashboard')
@section('title') {{ __('add_request') }} @endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Display general validation errors if there are any -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display exception error messages if any -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Display success messages if any -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('seller.storeProductRequests') }}" method="POST">
                        @csrf
                        <div class="d-md-flex align-items-center mb-4 row">
                        <div class="col-md-4 col-12"><img src="https://cdn.codpartner.com/assets/img/pngs/sourcing.png" style="width: 100%; height: auto;"></div>
                            <div class="col-md-8 col-12">
                                <h4 class="font-weight-bold mb-3">We Source For You</h4>
                                <p class="mb-4">Let our professional sourcing team take care of all your sourcing needs.</p>
                                
                                <div class="row">
                                    <div class="col-md-4 productnew">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Product Name*</label>
                                            <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Product name" value="{{ old('product_name') }}" required>
                                            @error('product_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="product_url" class="form-label">Product URL</label>
                                            <input type="text" id="product_url" name="product_url" class="form-control" placeholder="Product URL" value="{{ old('product_url') }}" required>
                                            @error('product_url')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="product_url" class="form-label">Product Image</label>
                                            <input type="file" id="product_url" name="product_url" class="form-control" placeholder="Product URL" value="{{ old('product_url') }}" required>
                                            @error('product_url')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                          <label for="category" class="form-label">Category</label>
                                            <select id="category" name="category" class="form-control">
                                                <option value="none" selected disabled hidden>Select a Category</option>
                                                <option value="Automobiles & Motorcycles" {{ old('category') == 'Automobiles & Motorcycles' ? 'selected' : '' }}>Automobiles & Motorcycles</option>
                                                <option value="Bag & Shoes" {{ old('category') == 'Bag & Shoes' ? 'selected' : '' }}>Bag & Shoes</option>
                                                <option value="Computer & Office" {{ old('category') == 'Computer & Office' ? 'selected' : '' }}>Computer & Office</option>
                                                <option value="Health & Beauty, Hair" {{ old('category') == 'Health & Beauty, Hair' ? 'selected' : '' }}>Health & Beauty, Hair</option>
                                                <option value="Home & Garden, Furniture" {{ old('category') == 'Home & Garden, Furniture' ? 'selected' : '' }}>Home & Garden, Furniture</option>
                                                <option value="Home Improvement" {{ old('category') == 'Home Improvement' ? 'selected' : '' }}>Home Improvement</option>
                                                <option value="Jewelry & Watches" {{ old('category') == 'Jewelry & Watches' ? 'selected' : '' }}>Jewelry & Watches</option>
                                                <option value="Men's Clothing" {{ old('category') == 'Men\'s Clothing' ? 'selected' : '' }}>Men's Clothing</option>
                                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                                <option value="Phones & Accessories" {{ old('category') == 'Phones & Accessories' ? 'selected' : '' }}>Phones & Accessories</option>
                                                <option value="Sports & Outdoors" {{ old('category') == 'Sports & Outdoors' ? 'selected' : '' }}>Sports & Outdoors</option>
                                                <option value="Toys, Kids & Baby" {{ old('category') == 'Toys, Kids & Baby' ? 'selected' : '' }}>Toys, Kids & Baby</option>
                                                <option value="Women's Clothing" {{ old('category') == 'Women\'s Clothing' ? 'selected' : '' }}>Women's Clothing</option>
                                            </select>
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity*</label>
                                            <input id="quantity" name="quantity" type="number" min="30" class="form-control" value="{{ old('quantity', 30) }}" required>
                                            @error('quantity')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="countryTo" class="form-label">Destination Country*</label>
                                            <select id="countryTo" name="countryTo" class="form-control" required>
                                                <option selected disabled hidden>Select a Country</option>
                                                @foreach($destinationCountries as $country)
                                                <option value="{{ $country->id }}" {{ old('countryTo') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('countryTo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="countryFrom" class="form-label">Sourcing Country*</label>
                                            <select id="countryFrom" name="countryFrom" class="form-control" required>
                                                <option selected disabled hidden>Select a Country</option>
                                                @foreach($sourcingCountries as $country)
                                                <option value="{{ $country->id }}" {{ old('countryFrom') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('countryFrom')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Note</label>
                                            <textarea id="note" name="note" class="form-control" rows="4" placeholder="Special request..">{{ old('note') }}</textarea>
                                            @error('note')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Shipping Method</label>
                                            @error('shipping_method')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipping_method" id="air_freight" value="Air freight" {{ old('shipping_method') == 'Air freight' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="air_freight">Air freight</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipping_method" id="ocean_freight" value="Ocean freight" {{ old('shipping_method') == 'Ocean freight' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ocean_freight">Ocean freight</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Request the Product</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
