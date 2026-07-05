<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Donasi;
use App\Models\OverlaySetting;

class OverlayController extends Controller {
    public function edit() {
        $user = Auth::user();
        $overlay = OverlaySetting::firstOrCreate(['user_id'=>$user->id],['posisi'=>'bottom-left','warna'=>'#4DC8F0','durasi'=>10]);
        if (empty($user->overlay_token)) { $user->overlay_token = Str::random(40); $user->save(); }
        return view('overlay.edit', compact('user','overlay'));
    }
    public function update(Request $request) {
        $request->validate(['posisi'=>'required|in:top-left,top-right,bottom-left,bottom-right','warna'=>'required|regex:/^#[0-9A-Fa-f]{6}$/','durasi'=>'required|integer|min:3|max:60']);
        OverlaySetting::updateOrCreate(['user_id'=>Auth::id()],['posisi'=>$request->posisi,'warna'=>$request->warna,'durasi'=>$request->durasi]);
        return back()->with('success','Pengaturan overlay berhasil disimpan!');
    }
    public function regenerateToken() {
        $user = Auth::user();
        $user->overlay_token = Str::random(40);
        $user->save();
        return response()->json(['token'=>$user->overlay_token,'url'=>route('overlay.display',$user->overlay_token)]);
    }
    public function display(string $token) {
        $user = User::where('overlay_token',$token)->firstOrFail();
        $overlay = OverlaySetting::firstOrCreate(['user_id'=>$user->id],['posisi'=>'bottom-left','warna'=>'#4DC8F0','durasi'=>10]);
        return view('overlay.display', compact('user','overlay'));
    }
    public function poll(string $token, Request $request) {
        $user = User::where('overlay_token',$token)->firstOrFail();
        $since = $request->query('since');
        $query = Donasi::where('user_id',$user->id)->where('payment_status','paid')->orderByDesc('created_at')->limit(1);
        if ($since) $query->where('created_at','>',date('Y-m-d H:i:s',(int)$since));
        $donasi = $query->first();
        if (!$donasi) return response()->json(['donasi'=>null]);
        return response()->json(['donasi'=>['id'=>$donasi->id,'donor_nama'=>$donasi->donor_nama,'donor_initial'=>$donasi->donor_initial,'donor_color'=>$donasi->donor_color,'jumlah'=>$donasi->jumlah,'jumlah_format'=>$donasi->jumlah_format,'pesan'=>$donasi->pesan,'created_at_ts'=>$donasi->created_at->timestamp]]);
    }
}
