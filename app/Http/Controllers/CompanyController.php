<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Holding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::whereNull('parent_id')->with(['holding', 'parent'])->get()->map(function ($c) {
            $label = $c->holding ? $c->holding->name : '-';
            if ($c->parent) {
                 $label .= ' -> ' . $c->parent->name;
            }

            return [
                'id' => $c->id,
                'name' => $c->name,
                'holding_label' => $label,
                'created_at' => $c->created_at->format('d/m/Y'),
            ];
        });

        $title = 'Data Perusahaan';
        return view('pages.organization.company.index', compact('companies', 'title'));
    }


    public function create(Request $request)
    {
        $holdings = Holding::all();
        $companies = Company::whereNull('parent_id')->get();

        $selectedHoldingId = $request->query('holding_id');
        return view('pages.organization.company.create', compact('holdings', 'selectedHoldingId', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'holding_id'=> 'required|exists:holdings,id',
            'parent_id' => 'nullable|exists:companies,id'
        ]);
        Company::create($request->only('name', 'holding_id', 'parent_id'));
        return redirect()->route('organization.company.index')->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        $company->load(['divisions', 'departments', 'units', 'children']);
        return view('pages.organization.company.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $holdings = Holding::all();
        $companies = Company::whereNull('parent_id')->where('id', '!=', $company->id)->get();
        return view('pages.organization.company.edit', compact('company', 'holdings', 'companies'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'holding_id'=> 'required|exists:holdings,id',
            'parent_id' => 'nullable|exists:companies,id'
        ]);
        $company->update($request->only('name', 'holding_id', 'parent_id'));
        return redirect()->route('organization.company.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        // Hapus gambar struktur jika ada
        if ($company->struktur_image) {
            Storage::disk('public')->delete($company->struktur_image);
        }
        $company->delete();
        return redirect()->route('organization.company.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Upload gambar struktur organisasi perusahaan.
     */
    public function uploadStruktur(Request $request, Company $company)
    {
        $request->validate([
            'struktur_image' => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp|max:5120',
        ], [
            'struktur_image.required' => 'Pilih file gambar terlebih dahulu.',
            'struktur_image.image'    => 'File harus berupa gambar.',
            'struktur_image.mimes'    => 'Format gambar yang didukung: JPG, PNG, GIF, SVG, WEBP.',
            'struktur_image.max'      => 'Ukuran gambar maksimal 5 MB.',
        ]);

        // Hapus gambar lama jika ada
        if ($company->struktur_image) {
            Storage::disk('public')->delete($company->struktur_image);
        }

        $path = $request->file('struktur_image')->store('struktur_perusahaan', 'public');
        $company->update(['struktur_image' => $path]);

        return redirect()
            ->route('organization.company.show', $company)
            ->with('success', 'Gambar struktur organisasi berhasil diupload.');
    }

    /**
     * Hapus gambar struktur organisasi perusahaan.
     */
    public function deleteStruktur(Company $company)
    {
        if ($company->struktur_image) {
            Storage::disk('public')->delete($company->struktur_image);
            $company->update(['struktur_image' => null]);
        }

        return redirect()
            ->route('organization.company.show', $company)
            ->with('success', 'Gambar struktur organisasi berhasil dihapus.');
    }
}
