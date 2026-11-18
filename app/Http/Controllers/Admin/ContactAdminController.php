<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactAdminController extends Controller
{
    // Menampilkan daftar pesan
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    // Menampilkan detail pesan
    public function show(Contact $contact)
    {
        // Tandai pesan sebagai sudah dibaca
        $contact->update([
            'is_read' => true
        ]);

        return view('admin.contacts.show', compact('contact'));
    }
}