<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display contact page
     */
    public function index()
    {
        // Contact information
        $contactInfo = [
            'phone' => '+62 812-3456-7890',
            'email' => 'info@motorspareparts.com',
            'address' => 'Jl. Otomotif No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'working_hours' => 'Senin - Sabtu: 08:00 - 17:00',
            'whatsapp' => '+6281234567890',
        ];

        // Frequently asked questions
        $faqs = [
            [
                'question' => 'Berapa lama waktu pengerjaan paket bore up?',
                'answer' => 'Waktu pengerjaan tergantung pada paket yang dipilih. Paket harian biasanya membutuhkan 2-3 hari, paket sport 3-4 hari, dan paket racing 5-7 hari.',
            ],
            [
                'question' => 'Apakah ada garansi untuk pengerjaan bore up?',
                'answer' => 'Ya, semua pengerjaan bore up mendapatkan garansi 6 bulan untuk pekerjaan dan komponen yang kami pasang.',
            ],
            [
                'question' => 'Bisakah saya memilih komponen sendiri untuk paket custom?',
                'answer' => 'Tentu saja! Untuk paket custom, Anda bisa berkonsultasi dengan tim kami untuk memilih komponen yang sesuai dengan kebutuhan dan budget Anda.',
            ],
            [
                'question' => 'Bagaimana cara melakukan pembayaran?',
                'answer' => 'Kami menerima pembayaran melalui transfer bank (BCA, Mandiri, BRI), kartu kredit, dan e-wallet (Gopay, OVO, Dana).',
            ],
            [
                'question' => 'Apakah sparepart yang dijual original?',
                'answer' => 'Ya, semua sparepart yang kami jual 100% original dengan garansi resmi dari masing-masing brand.',
            ],
        ];

        return view('contact.index', compact('contactInfo', 'faqs'));
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Save contact message to database (if you have a table)
            // ContactMessage::create($request->all());

            // Send email notification
            Mail::to(config('mail.from.address'))
                ->send(new ContactFormMail($request->all()));

            // Send confirmation to customer
            Mail::to($request->email)
                ->send(new ContactFormMail($request->all(), true));

            return back()->with('success', 'Pesan Anda berhasil dikirim. Kami akan menghubungi Anda dalam 1x24 jam.');

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.');
        }
    }

    /**
     * Handle WhatsApp click
     */
    public function whatsapp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        $whatsappNumber = config('app.whatsapp_number', '+6281234567890');
        $message = urlencode("Halo, saya {$request->name}.\n{$request->message}");

        $url = "https://wa.me/{$whatsappNumber}?text={$message}";

        return redirect($url);
    }
}
