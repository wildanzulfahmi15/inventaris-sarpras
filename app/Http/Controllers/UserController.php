<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\UserImport;
use App\Exports\UserTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserPreviewImport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('jurusan');

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where('nama', 'like', "%$q%")
                ->orWhere('role', 'like', "%$q%");
        }

        $users = $query->orderBy('nama')->paginate(10)->withQueryString();
        $jurusan = Jurusan::all();

        return view('sarpras.user.index', compact('users', 'jurusan'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'role' => 'required|in:guru,sarpras',
            'password' => 'required|min:6',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan'
        ]);

        User::create([
            'nama' => $request->nama,
            'role' => $request->role,
            'id_jurusan' => $request->role === 'guru' ? $request->id_jurusan : null,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'role' => 'required|in:guru,sarpras',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'nama' => $request->nama,
            'role' => $request->role,
            'id_jurusan' => $request->role === 'guru'
                ? $request->id_jurusan
                : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'Akun berhasil dihapus');
    }
    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new UserImport, $request->file('file'));

    return response()->json([
    'success' => true,
    'message' => 'Akun berhasil dihapus'
]);

    }

    public function template()
    {
        return Excel::download(new UserTemplateExport, 'template_akun.xlsx');
    }
    public function preview(Request $request)
    {
    if (!$request->hasFile('file')) {
        return response()->json([]);
    }

    $import = new UserPreviewImport();
    Excel::import($import, $request->file('file'));

    return response()->json($import->rows);
    }
public function saveToken(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $token = $request->input('token');

    if (!$token) {
        return response()->json(['error' => 'Token kosong'], 400);
    }

    // 🔥 HAPUS TOKEN YANG SAMA DI USER LAIN
    \App\Models\User::where('fcm_token', $token)
        ->update(['fcm_token' => null]);

    // 🔥 SET TOKEN KE USER SEKARANG
    $user->update([
        'fcm_token' => $token
    ]);

    return response()->json(['success' => true]);
}
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // password default
        $newPassword = '123456';

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return response()->json([
    'success' => true,
    'message' => 'Akun berhasil dihapus'
]);

    }


}
