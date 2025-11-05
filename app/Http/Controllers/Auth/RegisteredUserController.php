<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation rules, including conditional validation for user_type
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
            'address' => ['required', 'string'],
            'user_type' => ['required', Rule::in(['particular', 'company'])], // Ensure it's either 'particular' or 'company'
        ];

        // Conditional validation for 'identity_perso' and 'company_document' based on user type
        if ($request->user_type === 'particular') {
            $validationRules['identity_perso'] = 'nullable|mimes:jpeg,png,jpg,pdf|max:2048'; // Personal Identity Image
        } elseif ($request->user_type === 'company') {
            $validationRules['company_document'] = 'nullable|mimes:jpeg,png,jpg,pdf|max:2048'; // Company Document
        }

        // Validate the request with the rules
        $request->validate($validationRules);

        // Prepare data for user creation
        $user = new User();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->role = 3; // Default role value as 3
        $user->user_type = $request->user_type;
        $user->password = Hash::make($request->password);

        // Check if the user is a 'particular' or 'company' type and handle file uploads
        if ($request->user_type === 'particular') {
            // Handle image upload for particular
            if ($request->hasFile('identity_perso')) {
                $image = $request->file('identity_perso');
                $imageName = $request->name . '_perso' . Auth::id() . '_' . Carbon::now()->format('YmdHis') . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/sellers/' . $request->name, $imageName);
                $user->identity_perso = $imagePath;
            }
        } elseif ($request->user_type === 'company') {
            // Handle document upload for company
            if ($request->hasFile('company_document')) {
                $document = $request->file('company_document');
                $documentName = $request->name . '_company' . Auth::id() . '_' . Carbon::now()->format('YmdHis') . '.' . $document->getClientOriginalExtension();
                $documentPath = $document->storeAs('public/sellers/' . $request->name, $documentName);
                $user->company_document = $documentPath;
            }
        }

        // Save the user to the database
        try {
            $user->save();  // Save the user instance to the database

            // Trigger email verification (optional)
            event(new Registered($user));

            // Log the user in after registration
            Auth::login($user);

            // Redirect to dashboard or other page
            return redirect(route('dashboard'));
        } catch (\Exception $e) {
            // Handle any errors and redirect back with the error message
            return back()->withErrors(['error' => 'There was an issue creating your account. Please try again.'])->withInput();
        }
    }
}
