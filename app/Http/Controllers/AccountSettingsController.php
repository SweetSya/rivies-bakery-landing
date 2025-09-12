<?php

namespace App\Http\Controllers;

use App\ValidatesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class AccountSettingsController extends RiviesAPIController
{
    use ValidatesImageUpload;

    public function __construct()
    {
        parent::__construct();
    }
    // Views for Account Settings
    public function view(Request $request)
    {
        $response = $this->apiGet("account-settings/profile", aborting: false);
        $user = $response->json()['user'] ?? null;
        return Inertia::render('AccountSettings/AccountSettingsInformation', [
            'user' => $user
        ]);
    }

    public function address_view(Request $request)
    {
        $response = $this->apiGet("account-settings/address", aborting: false);
        $addresses = $response->json()['addresses'] ?? [];
        return Inertia::render('AccountSettings/AccountSettingsAddress', [
            'addresses' => $addresses
        ]);
    }

    public function transactions_view(Request $request)
    {
        $response = $this->apiGet("account-settings/transactions", aborting: false);
        $orders = $response->json()['orders'] ?? [];
        return Inertia::render('AccountSettings/AccountSettingsTransaction', [
            'orders' => $orders
        ]);
    }

    public function vouchers_view(Request $request)
    {
        $response = $this->apiGet("vouchers/user-vouchers", aborting: false);
        $vouchers = $response->json()['vouchers'] ?? [];
        return Inertia::render('AccountSettings/AccountSettingsVouchers', [
            'vouchers' => $vouchers
        ]);
    }

    // API Endpoints for update user information
    public function update_profile(Request $request)
    {
        // Basic validation first
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'new_profile_picture' => $this->getImageValidationRules([
                'required' => false,
                'max_size' => 2048, // 2MB
                'dimensions' => 'min_width=100,min_height=100,max_width=2000,max_height=2000'
            ]),
        ], [
            'new_profile_picture.image' => 'File harus berupa gambar.',
            'new_profile_picture.mimes' => 'Format gambar harus JPEG, PNG, GIF, atau WebP.',
            'new_profile_picture.max' => 'Ukuran gambar maksimal 2MB.',
            'new_profile_picture.dimensions' => 'Dimensi gambar harus antara 100x100 dan 2000x2000 pixel.',
        ]);

        // Handle image upload with additional security validation
        if ($request->hasFile('new_profile_picture')) {
            $file = $request->file('new_profile_picture');

            // Additional security validation using our trait
            $imageValidation = $this->validateSecureImage($file);

            if (!$imageValidation['valid']) {
                return response()->json([
                    'error' => 'File gambar tidak valid',
                    'errors' => $imageValidation['errors']
                ], 422);
            }

            // File is valid, keep it in the validated data as UploadedFile
            $validated['new_profile_picture'] = $file;
        } else {
            // No file uploaded, ensure it's not in the validated data
            unset($validated['new_profile_picture']);
        }
        // Send to API with file (using multipart/form-data)
        $response = $this->apiPost(
            "account-settings/profile/update",
            $validated,
            aborting: false
        );
        $data = $response->json();
        if ($response->successful()) {
            $jwt = [
                'user' => $data['user'] ?? null,
                'token' => $data['token'] ?? null,
            ];
            Cookie::forget('session_token'); // Clear old cookie if any
            Cookie::queue('session_token', json_encode($jwt), auth()->guard(env('API_APP', 'bakery-store'))->factory()->getTTL(), '/', null, true, true);
            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'user' => $response->json()['user'] ?? null,
            ]);
        }

        return response()->json([
            'error' => 'Gagal memperbarui profil',
            'details' => $response->json()
        ], $response->status());
    }

    // API Endpoints for managing addresses
    public function create_address(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'recipientName' => 'required|string|max:100',
            'phoneNumber' => 'nullable|numeric',
            'fullAddress' => 'required|string|max:255',
            'isMain' => 'required|boolean',
            'hasPinpoint' => 'required|boolean',
            'pinpointLocation.lat' => 'nullable|numeric',
            'pinpointLocation.lng' => 'nullable|numeric',
        ], [
            'label.required' => 'Label alamat diperlukan.',
            'recipientName.required' => 'Nama penerima diperlukan.',
            'fullAddress.required' => 'Alamat lengkap diperlukan.',
            'isMain.required' => 'Status utama diperlukan.',
        ]);
        $response = $this->apiPost(
            "account-settings/address/create",
            $validated,
            aborting: false
        );
        $data = $response->json();
        if ($response->successful()) {
            return response()->json([
                'message' => 'Alamat berhasil ditambahkan',
                'addresses' => $data['addresses'] ?? []
            ]);
        }

        return response()->json([
            'error' => 'Gagal menambahkan alamat',
            'details' => $data
        ], $response->status());
    }
    public function update_address(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'label' => 'required|string|max:100',
            'recipientName' => 'required|string|max:100',
            'phoneNumber' => 'nullable|numeric',
            'fullAddress' => 'required|string|max:255',
            'isMain' => 'required|boolean',
            'hasPinpoint' => 'boolean',
            'pinpointLocation.lat' => 'nullable|numeric',
            'pinpointLocation.lng' => 'nullable|numeric',
        ], [
            'label.required' => 'Label alamat diperlukan.',
            'recipientName.required' => 'Nama penerima diperlukan.',
            'fullAddress.required' => 'Alamat lengkap diperlukan.',
            'isMain.required' => 'Status utama diperlukan.',
        ]);
        $response = $this->apiPost(
            "account-settings/address/update",
            $validated,
            aborting: false
        );

        $data = $response->json();
        if ($response->successful()) {
            return response()->json([
                'message' => 'Alamat berhasil diperbarui',
                'addresses' => $data['addresses'] ?? []
            ]);
        }

        return response()->json([
            'error' => 'Gagal memperbarui alamat',
            'details' => $data
        ], $response->status());
    }

    public function delete_address(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ], [
            'id.required' => 'ID alamat diperlukan.',
        ]);

        $response = $this->apiPost(
            "account-settings/address/delete",
            $validated,
            aborting: false
        );

        $data = $response->json();
        if ($response->successful()) {
            return response()->json([
                'message' => 'Alamat berhasil dihapus',
                'addresses' => $data['addresses'] ?? []
            ]);
        }

        return response()->json([
            'error' => 'Gagal menghapus alamat',
            'details' => $data
        ], $response->status());
    }

    public function transactions_detail(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required',
        ], [
            'invoice_number.required' => 'No Invoice pesanan diperlukan.',
        ]);

        $response = $this->apiPost(
            "account-settings/transactions/get",
            $validated,
            aborting: false
        );

        $data = $response->json();
        if ($response->successful()) {
            return response()->json([
                'order' => $data['orders'][0] ?? null
            ]);
        }

        return response()->json([
            'error' => 'Gagal mengambil detail pesanan',
            'details' => $data
        ], $response->status());
    }
    public function transactions_get_payment(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required',
        ], [
            'invoice_number.required' => 'No Invoice pesanan diperlukan.',
        ]);

        $response = $this->apiPost(
            "account-settings/transactions/get-payment",
            $validated,
            aborting: false
        );

        $data = $response->json();
        if ($response->successful()) {
            return response()->json([
                'order' => $data['orders'][0] ?? null
            ]);
        }

        return response()->json([
            'error' => 'Gagal mengambil detail pesanan',
            'details' => $data
        ], $response->status());
    }
}
