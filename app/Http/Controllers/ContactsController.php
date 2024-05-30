<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contact' => 'required|unique:contacts|max:255', // Add validation rules as needed
        ]);

        // Store the contact information in the database
        $contact = new Contact();
        $contact->contact = $request->contact;
        $contact->save();

        return redirect()->back()->with('success', 'Contact information added successfully.');
    }
}
