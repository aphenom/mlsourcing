@extends('auth.theme.dashboard')
@section('content')
<div class="container mt-4">
   <!-- Display Flash Messages -->
   @if (session('success'))
   <div class="alert alert-success alert-dismissible fade show" role="alert">
      <span class="alert-text" style="color:white!important;">{{ session('success') }}</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
   </div>
   @endif
   <!-- Display Errors -->
   @if ($errors->any())
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
      @foreach ($errors->all() as $error)
      <span class="alert-text" style="color:white!important;">{{ $error }}</span><br>
      @endforeach
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
   </div>
   @endif
   <div class="row">
      <!-- Display Sourcing Countries Section -->
      <div class="col-lg-6 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.sourcing_countries') }}</div>
            <div class="card-body">
               <ul class="list-group">
                  @forelse($sourcingCountries as $country)
                  <li class="list-group-item d-flex justify-content-between align-items-center text-sm mb-0 text-capitalize">
                     {{ $country->country_name }} ({{ $country->country_code }})
                     <form action="{{ route('admin.deleteSourcingCountry', $country->id) }}" method="POST" class="mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDelete(event, '{{ $country->country_name }}')">{{ __('pages.delete') }}</button>
                     </form>
                  </li>
                  @empty
                  <li class="list-group-item">{{ __('pages.no_sourcing_available') }}</li>
                  @endforelse
               </ul>
               <!-- Add Sourcing Country Form -->
               <div class="mt-4">
                  <h5>{{ __('pages.add_sourcing_country') }}</h5>
                  <form action="{{ route('admin.addSourcingCountry') }}" method="POST">
                     @csrf
                     <div class="mb-3">
                        <label for="sourcing_country_code" class="form-label">{{ __('pages.country_code') }}</label>
                        <input type="text" id="sourcing_country_code" name="sourcing_country_code" class="form-control" required>
                        @error('sourcing_country_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <div class="mb-3">
                        <label for="sourcing_country_name" class="form-label">{{ __('pages.country_name_label') }}</label>
                        <input type="text" id="sourcing_country_name" name="sourcing_country_name" class="form-control" required>
                        @error('sourcing_country_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <button type="submit" class="btn btn-primary">{{ __('pages.add_sourcing_country') }}</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Display Destination Countries Section -->
      <div class="col-lg-6 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.destination_countries') }}</div>
            <div class="card-body">
               <ul class="list-group">
                  @forelse($destinationCountries as $country)
                  <li class="list-group-item d-flex justify-content-between align-items-center text-sm mb-0 text-capitalize">
                     {{ $country->country_name }} ({{ $country->country_code }})
                     <form action="{{ route('admin.deleteDestinationCountry', $country->id) }}" method="POST" class="mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDelete(event, '{{ $country->country_name }}')">{{ __('pages.delete') }}</button>
                     </form>
                  </li>
                  @empty
                  <li class="list-group-item">{{ __('pages.no_destination_available') }}</li>
                  @endforelse
               </ul>
               <!-- Add Destination Country Form -->
               <div class="mt-4">
                  <h5>{{ __('pages.add_destination_country') }}</h5>
                  <form action="{{ route('admin.addDestinationCountry') }}" method="POST">
                     @csrf
                     <div class="mb-3">
                        <label for="destination_country_code" class="form-label">{{ __('pages.country_code') }}</label>
                        <input type="text" id="destination_country_code" name="destination_country_code" class="form-control" required>
                        @error('destination_country_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <div class="mb-3">
                        <label for="destination_country_name" class="form-label">{{ __('pages.country_name_label') }}</label>
                        <input type="text" id="destination_country_name" name="destination_country_name" class="form-control" required>
                        @error('destination_country_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <button type="submit" class="btn btn-primary">{{ __('pages.add_destination_country') }}</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Add Agent Section -->
      <div class="col-lg-6 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.add_agent') }}</div>
            <div class="card-body">
               <form action="{{ route('admin.storeAgent') }}" method="POST">
                  @csrf
                  <!-- Agent Name -->
                  <div class="mb-3">
                     <label for="agent_name" class="form-label">{{ __('pages.agent_name_label') }}</label>
                     <input type="text" class="form-control" id="agent_name" name="agent_name" required>
                  </div>
                  <!-- Agent Email -->
                  <div class="mb-3">
                     <label for="agent_email" class="form-label">{{ __('pages.agent_email_label') }}</label>
                     <input type="email" class="form-control" id="agent_email" name="agent_email" required>
                  </div>
                  <!-- Agent Phone -->
                  <div class="mb-3">
                     <label for="agent_phone" class="form-label">{{ __('pages.agent_phone_label') }} (ex : +212600000000)</label>
                     <input type="phone" class="form-control" id="agent_phone" name="agent_phone" required>
                  </div>
                  <!-- Address -->
                  <div class="form-group">
                     <label for="address">{{ __('pages.address_label') }}</label>
                     <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                  </div>
                  <!-- Company Name -->
                  <div class="form-group">
                     <label for="company_name">{{ __('pages.company_name_label') }}</label>
                     <input type="text" id="company_name" name="company_name" class="form-control" required>
                  </div>
                  <!-- Company Information -->
                  <div class="form-group">
                     <label for="company_information">{{ __('pages.company_information_label') }}</label>
                     <textarea id="company_information" name="company_information" class="form-control" rows="4" required></textarea>
                  </div>
                  <!-- Sourcing Country -->
                  <div class="mb-3">
                     <label for="sourcing_country" class="form-label">{{ __('pages.sourcing_country_filter') }}*</label>
                     <select class="form-select" id="sourcing_country" name="sourcing_country" required>
                        <option value="" selected disabled>{{ __('pages.select_sourcing_country_opt') }}</option>
                        @foreach($sourcingCountries as $country)
                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <!-- Destination Countries -->
                  <div class="mb-3">
                     <label for="destination_countries" class="form-label">{{ __('pages.destination_countries_label') }}</label>
                     <select class="form-select" id="destination_countries" name="destination_countries[]" multiple required>
                        @foreach($destinationCountries as $country)
                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                     </select>
                     <p>{{ __('pages.ctrl_select') }}</p>
                     <p>{{ __('pages.default_agent_password') }}</p>
                  </div>
                  <!-- Submit Button -->
                  <button type="submit" class="btn btn-primary">{{ __('pages.add_agent') }}</button>
               </form>
            </div>
         </div>
      </div>
      <!-- Manage Agent Section -->
      <div class="col-lg-6 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.link_agent_dest') }}</div>
            <div class="card-body">
               <form action="{{ route('admin.linkDestinationCountries') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                     <label for="agent_id" class="form-label">{{ __('pages.select_agent') }}</label>
                     <select class="form-select" id="agent_id" name="agent_id" required>
                        <option value="" selected disabled>{{ __('pages.select_agent') }}</option>
                        @foreach($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->email }})</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="mb-3">
                     <label for="new_destination_countries" class="form-label">{{ __('pages.select_destination_countries') }}</label>
                     <select class="form-select" id="new_destination_countries" name="destination_countries[]" multiple required>
                        @foreach($destinationCountries as $country)
                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <button type="submit" class="btn btn-primary">{{ __('pages.link_selected_countries') }}</button>
               </form>
            </div>
         </div>
      </div>
      <!-- Display Payment Options -->
      <div class="col-lg-12 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.payment_options') }}</div>
            <div class="card-body table-responsive">
               <table class="table align-items-center mb-0">
                  <thead>
                     <tr>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.image_col') }}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.name_col') }}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.details_col') }}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.action_col') }}</th>
                     </tr>
                  </thead>
                  <tbody class="text-center">
                     @foreach($paymentOptions as $option)
                     <tr>
                        <td><img src="{{ asset('storage/' . $option->image) }}" alt="{{ $option->name }}" style="width: 50px;"></td>
                        <td>
                           <p class="text-sm mb-0">{{ $option->name }}</p>
                        </td>
                        <td>
                           @foreach(json_decode($option->details, true) as $key => $value)
                           <strong class="text-sm mb-0">{{ $key }}:</strong> <span class="text-sm mb-0">{{ is_array($value) ? json_encode($value) : $value }}</span><br>
                           @endforeach
                        </td>
                        <td>
                           <form action="{{ route('admin.deletePaymentOption',$option->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment option?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm">{{ __('pages.delete') }}</button>
                           </form>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- Add Payment Options -->
      <div class="col-lg-12 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.payment_options') }}</div>
            <div class="card-body">
               <form id="add-payment-option-form" method="POST" action="{{ route('admin.addPaymentOption') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                     <label for="name">{{ __('pages.payment_name_label') }}:</label>
                     <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="image">{{ __('pages.upload_image_label') }}:</label>
                    <input type="file" class="form-control" id="image" name="image" accept=".jpeg, .jpg, .png" required>
                  </div>
                  <div class="form-group" id="dynamic-fields">
                     <!-- Static key-value pair, always required -->
                     <div class="form-group row">
                        <label for="name">{{ __('pages.details_label') }} :</label>
                        <div class="col-md-5">
                           <input type="text" class="form-control" name="keys[]" placeholder="Key (e.g., Account Name)" required>
                        </div>
                        <div class="col-md-5">
                           <input type="text" class="form-control" name="values[]" placeholder="Value (e.g., John Doe)" required>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <button type="button" class="btn btn-secondary" id="add-field-btn">{{ __('pages.add_field') }}</button>
                  </div>
                  <button type="submit" class="btn btn-primary">{{ __('pages.add_payment_option') }}</button>
               </form>
            </div>
         </div>
      </div>
      <!-- Add Comptable Section -->
      <div class="col-lg-6 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.add_comptable') }}</div>
            <div class="card-body">
               <form action="{{ route('admin.storeComptable') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                     <label for="comptable_name" class="form-label">{{ __('pages.comptable_name_label') }}</label>
                     <input type="text" class="form-control" id="comptable_name" name="comptable_name" required>
                  </div>
                  <div class="mb-3">
                     <label for="comptable_email" class="form-label">{{ __('pages.comptable_email_label') }}</label>
                     <input type="email" class="form-control" id="comptable_email" name="comptable_email" required>
                  </div>
                  <div class="mb-3">
                     <label for="comptable_phone" class="form-label">{{ __('pages.comptable_phone_label') }}</label>
                     <input type="text" class="form-control" id="comptable_phone" name="comptable_phone" required>
                  </div>
                  <p class="text-sm text-muted">{{ __('pages.comptable_password_hint') }}</p>
                  <button type="submit" class="btn btn-primary">{{ __('pages.add_comptable') }}</button>
               </form>
            </div>
         </div>
      </div>
      <!-- Comptable Listing Section -->
      <div class="col-lg-6 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.comptables') }}</div>
            <div class="card-body">
               <table class="table align-items-center mb-0">
                  <thead class="text-center">
                     <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.action_col') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.agent_name_col') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('pages.agent_email_col') }}</th>
                     </tr>
                  </thead>
                  <tbody class="text-center">
                     @forelse($comptables as $comptable)
                     <tr>
                        <td>
                           <form action="{{ route('admin.deleteComptable', $comptable->id) }}" method="POST" onsubmit="return confirm('{{ __('pages.confirm_delete_comptable') }}')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm">{{ __('pages.delete') }}</button>
                           </form>
                        </td>
                        <td>
                           <p class="text-xs font-weight-bold mb-0">{{ $comptable->name }}</p>
                        </td>
                        <td>
                           <p class="text-xs font-weight-bold mb-0">{{ $comptable->email }}</p>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="3" class="text-center text-sm">{{ __('pages.no_comptables') }}</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- See Linking Agent Section -->
      <div class="col-lg-12 col-md-12 mb-4">
         <div class="card">
            <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.agents_countries') }}</div>
            <div class="card-body">
               <table class="table align-items-center mb-0">
                  <thead class="text-center">
                     <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.action_col') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.agent_name_col') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('pages.agent_email_col') }}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.sourcing_country_filter') }}</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('pages.destination_countries') }}</th>
                     </tr>
                  </thead>
                  <tbody class="text-center">
                     @foreach($agents as $agent)
                     <tr>
                        <td>
                           <form action="{{ route('admin.deleteAgent', $agent->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this agent?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm">{{ __('pages.delete_agent') }}</button>
                           </form>
                        </td>
                        <td>
                           <p class="text-xs font-weight-bold mb-0">{{ $agent->name }}</p>
                        </td>
                        <td>
                           <p class="text-xs font-weight-bold mb-0">{{ $agent->email }}</p>
                        </td>
                        <td>
                           @if ($agent->sourcingCountries->isEmpty())
                           <form action="{{ route('admin.linkSourcingCountry', $agent->id) }}" method="POST">
                              @csrf
                              <select class="form-select mb-2" name="sourcing_country_id" required>
                                 <option value="" selected disabled>{{ __('pages.select_sourcing_country_opt') }}</option>
                                 @foreach($sourcingCountries as $country)
                                 <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                 @endforeach
                              </select>
                              <button type="submit" class="btn btn-success btn-sm">{{ __('pages.link_sourcing_country_btn') }}</button>
                           </form>
                           @else
                           <p class="text-xs font-weight-bold mb-0">{{ $agent->sourcingCountries->first()->country_name }}</p>
                           @endif
                        </td>
                        <td>
                           <ul class="list-group">
                              @foreach($agent->destinationCountries as $destinationCountry)
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                 <p class="text-xs font-weight-bold mb-0">
                                    {{ $destinationCountry->country_name }}
                                 </p>
                                 @if($agent->destinationCountries->count() > 1)
                                 <form action="{{ route('admin.unlinkDestinationCountry', [$agent->id, $destinationCountry->id]) }}" method="POST" class="mb-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('pages.unlink') }}</button>
                                 </form>
                                 @endif
                              </li>
                              @endforeach
                           </ul>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
               <!--  -->
            </div>
         </div>
      </div>
   </div>
</div>
@push('scripts')
<script>
   $(document).ready(function() {
       document.getElementById('add-field-btn').addEventListener('click', function() {
       const dynamicFields = document.getElementById('dynamic-fields');
   
       const formGroup = document.createElement('div');
       formGroup.className = 'form-group row';
   
       const keyInputDiv = document.createElement('div');
       keyInputDiv.className = 'col-md-5';
       const keyInput = document.createElement('input');
       keyInput.type = 'text';
       keyInput.className = 'form-control';
       keyInput.name = 'keys[]';
       keyInput.placeholder = 'Key (e.g., Account Name)';
       keyInput.required = true;
       keyInputDiv.appendChild(keyInput);
   
       const valueInputDiv = document.createElement('div');
       valueInputDiv.className = 'col-md-5';
       const valueInput = document.createElement('input');
       valueInput.type = 'text';
       valueInput.className = 'form-control';
       valueInput.name = 'values[]';
       valueInput.placeholder = 'Value (e.g., John Doe)';
       valueInput.required = true;
       valueInputDiv.appendChild(valueInput);
   
       const removeBtnDiv = document.createElement('div');
       removeBtnDiv.className = 'col-md-2';
       const removeBtn = document.createElement('button');
       removeBtn.type = 'button';
       removeBtn.className = 'btn btn-danger';
       removeBtn.innerText = 'Remove';
       removeBtn.addEventListener('click', function() {
           dynamicFields.removeChild(formGroup);
       });
       removeBtnDiv.appendChild(removeBtn);
   
       formGroup.appendChild(keyInputDiv);
       formGroup.appendChild(valueInputDiv);
       formGroup.appendChild(removeBtnDiv);
   
       dynamicFields.appendChild(formGroup);
   });
   
   });
</script>
@endpush
@endsection