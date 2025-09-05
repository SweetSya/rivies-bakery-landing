<?php

namespace App\Http\Controllers;

use App\ValidatesImageUpload;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountSettingsController extends RiviesAPIController
{

    public function __construct()
    {
        parent::__construct();
    }
    use ValidatesImageUpload;
    // Views for Account Settings
    public function view(Request $request)
    {
        return Inertia::render('AccountSettings/AccountSettingsInformation');
    }

    public function address_view(Request $request)
    {
        return Inertia::render('AccountSettings/AccountSettingsAddress');
    }

    public function transactions_view(Request $request)
    {
        return Inertia::render('AccountSettings/AccountSettingsTransaction');
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
        }

        // Send to API with file (using multipart/form-data)
        $response = $this->apiPost(
            "account-settings/update-profile",
            $validated,
            aborting: false
        );

        if ($response->successful()) {
            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'data' => $response->json()
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
            'id' => 'required',
            'label' => 'required|string|max:100',
            'recipientName' => 'required|string|max:100',
            'fullAddress' => 'required|string|max:255',
            'isMain' => 'required|boolean',
            'hasPinPoint' => 'boolean|default:false',
            'pinpointLocation.lat' => 'nullable|numeric',
            'pinpointLocation.lng' => 'nullable|numeric',
        ], [
            'label.required' => 'Label alamat diperlukan.',
            'recipientName.required' => 'Nama penerima diperlukan.',
            'fullAddress.required' => 'Alamat lengkap diperlukan.',
            'isMain.required' => 'Status utama diperlukan.',
        ]);
        dd($validated);
        // $response = $this->apiPost(
        //     "account-settings/create-address",
        //     $validated,
        //     aborting: false
        // );

        if ($response->successful()) {
            return response()->json([
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $response->json()
            ]);
        }

        return response()->json([
            'error' => 'Gagal menambahkan alamat',
            'details' => $response->json()
        ], $response->status());
    }
    public function update_address(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'label' => 'required|string|max:100',
            'recipientName' => 'required|string|max:100',
            'fullAddress' => 'required|string|max:255',
            'isMain' => 'required|boolean',
            'hasPinPoint' => 'boolean|default:false',
            'pinpointLocation.lat' => 'nullable|numeric',
            'pinpointLocation.lng' => 'nullable|numeric',
        ], [
            'label.required' => 'Label alamat diperlukan.',
            'recipientName.required' => 'Nama penerima diperlukan.',
            'fullAddress.required' => 'Alamat lengkap diperlukan.',
            'isMain.required' => 'Status utama diperlukan.',
        ]);
        dd($validated);
        // $response = $this->apiPost(
        //     "account-settings/update-address",
        //     $validated,
        //     aborting: false
        // );

        if ($response->successful()) {
            return response()->json([
                'message' => 'Alamat berhasil diperbarui',
                'data' => $response->json()
            ]);
        }

        return response()->json([
            'error' => 'Gagal memperbarui alamat',
            'details' => $response->json()
        ], $response->status());
    }

    public function delete_address(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ], [
            'id.required' => 'ID alamat diperlukan.',
        ]);
        dd($validated);
        // $response = $this->apiPost(
        //     "account-settings/delete-address",
        //     $validated,
        //     aborting: false
        // );

        if ($response->successful()) {
            return response()->json([
                'message' => 'Alamat berhasil dihapus',
                'data' => $response->json()
            ]);
        }

        return response()->json([
            'error' => 'Gagal menghapus alamat',
            'details' => $response->json()
        ], $response->status());
    }
}
