<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Menampilkan halaman contact
    public function create()
    {
        return view('contact');
    }

    // Menyimpan pesan contact
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'nullable|max:200',
            'message' => 'required|min:10',
        ]);

        Contact::create($validated);

        return back()->with(
            'success',
            'Pesan berhasil dikirim. Terima kasih!'
        );
    }
}