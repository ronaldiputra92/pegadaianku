<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerDocumentController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Simple test first - check if we can access the model
            $documents = CustomerDocument::paginate(10);
            return view('customer-documents.index', compact('documents'));
        } catch (\Exception $e) {
            // If there's an error, return a simple response
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        return view('customer-documents.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'document_type' => 'required|in:ktp,sim,passport,kk,npwp',
            'document_number' => 'required|string|max:50',
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'notes' => 'nullable|string|max:500'
        ]);

        // Upload file
        $file = $request->file('document_file');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('customer-documents', $filename, 'public');

        CustomerDocument::create([
            'customer_id' => $request->customer_id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'document_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'notes' => $request->notes,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('customer-documents.index')
            ->with('success', 'Dokumen berhasil diupload.');
    }

    public function show(CustomerDocument $customerDocument)
    {
        return view('customer-documents.show', compact('customerDocument'));
    }

    public function edit(CustomerDocument $customerDocument)
    {
        $customers = Customer::orderBy('name')->get();
        return view('customer-documents.edit', compact('customerDocument', 'customers'));
    }

    public function update(Request $request, CustomerDocument $customerDocument)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'document_type' => 'required|in:ktp,sim,passport,kk,npwp',
            'document_number' => 'required|string|max:50',
            'document_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:500'
        ]);

        $data = [
            'customer_id' => $request->customer_id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'notes' => $request->notes,
        ];

        // Upload file baru jika ada
        if ($request->hasFile('document_file')) {
            // Hapus file lama
            if ($customerDocument->document_path && Storage::disk('public')->exists($customerDocument->document_path)) {
                Storage::disk('public')->delete($customerDocument->document_path);
            }

            $file = $request->file('document_file');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('customer-documents', $filename, 'public');

            $data['document_path'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $customerDocument->update($data);

        return redirect()->route('customer-documents.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(CustomerDocument $customerDocument)
    {
        // Hapus file
        if ($customerDocument->document_path && Storage::disk('public')->exists($customerDocument->document_path)) {
            Storage::disk('public')->delete($customerDocument->document_path);
        }

        $customerDocument->delete();

        return redirect()->route('customer-documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(CustomerDocument $customerDocument)
    {
        if (!$customerDocument->document_path || !Storage::disk('public')->exists($customerDocument->document_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $customerDocument->document_path,
            $customerDocument->original_filename
        );
    }

    public function verify(CustomerDocument $customerDocument)
    {
        $customerDocument->verify();
        
        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diverifikasi.'
        ]);
    }

    public function getByCustomer(Customer $customer)
    {
        $documents = $customer->documents()->latest()->get();
        return view('customer-documents.by-customer', compact('customer', 'documents'));
    }

    /**
     * Serve document file directly (fallback for storage link issues)
     */
    public function serveFile(CustomerDocument $customerDocument)
    {
        if (!$customerDocument->document_path || !Storage::disk('public')->exists($customerDocument->document_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $file = Storage::disk('public')->get($customerDocument->document_path);
        $mimeType = $customerDocument->mime_type ?: 'application/octet-stream';

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $customerDocument->original_filename . '"');
    }
}