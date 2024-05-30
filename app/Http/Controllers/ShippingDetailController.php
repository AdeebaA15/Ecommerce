<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingDetail;

class ShippingDetailController extends Controller
{
    /**
     * Display the shipping details form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve the latest shipping details from the database
        $shippingDetail = ShippingDetail::latest()->first();
        
        // Pass the shipping details to the view
        return view('shipping_details.index', compact('shippingDetail'));
    }

    /**
     * Store the shipping details in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
        ]);
    
        // Create a new shipping detail record
        ShippingDetail::create($validatedData);
    
        // Redirect back with success message
        return back()->with('success', 'Shipping details updated successfully.');
    }
}
