<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donasi;
use Illuminate\Http\Request;

class AdminUserController extends Controller {
    public function index(Request $request) {
        $q = User::where('role', 'user')->withCount(['donasi as total_donasi' => function($q){ $q->where('payment_status','paid'); }])->withSum(['donasi as total_nominal' => function($q){ $q->where('payment_status','paid'); }], 'jumlah');
        if ($request->search) $q->where(function($q) use ($request){ $q->where('nama','like',"%{$request->search}%")->orWhere('username','like',"%{$request->search}%")->orWhere('email','like',"%{$request->search}%"); });
        if ($request->status === 'suspended') $q->where('is_suspended', true);
        if ($request->status === 'active') $q->where('is_suspended', false);
        $users = $q->orderByDesc('created_at')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user) {
        $donasi = Donasi::where('user_id',$user->id)->where('payment_status','paid')->orderByDesc('paid_at')->paginate(15);
        $totalNominal = Donasi::where('user_id',$user->id)->where('payment_status','paid')->sum('jumlah');
        return view('admin.users.show', compact('user','donasi','totalNominal'));
    }

    public function suspend(User $user) {
        if ($user->isAdmin()) return back()->with('error','Tidak bisa suspend admin.');
        $user->update(['is_suspended' => !$user->is_suspended]);
        $status = $user->is_suspended ? 'disuspend' : 'diaktifkan';
        return back()->with('success',"User {$user->nama} berhasil {$status}.");
    }

    public function destroy(User $user) {
        if ($user->isAdmin()) return back()->with('error','Tidak bisa hapus admin.');
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User berhasil dihapus.');
    }
}
