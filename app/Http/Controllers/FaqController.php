<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        // Fetch all FAQs from the database
        $faqs = Faq::all();
        // Return the FAQ view with the FAQs data
        return view('auth.seller.faqs', compact('faqs'));
    }
}
