<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['required', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // TODO: kirim email / simpan ke database sesuai kebutuhan.

        return redirect()
            ->route('contact')
            ->with('success', 'Terima kasih, pesan Anda telah berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}
