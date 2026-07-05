<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller {
    public function index() {
        $settings = [
            'duitku_merchant_code' => Setting::get('duitku_merchant_code'),
            'duitku_api_key'       => Setting::get('duitku_api_key'),
            'duitku_env'           => Setting::get('duitku_env', 'sandbox'),
            'platform_fee_pct'     => Setting::get('platform_fee_pct', '0'),
            'maintenance_mode'     => Setting::get('maintenance_mode', '0'),
            'announcement'         => Setting::get('announcement', ''),
            'payment_methods'      => json_decode(Setting::get('payment_methods', '[]'), true),
            'min_donate_ov'        => Setting::get('min_donate_ov', '10000'),
            'min_donate_da'        => Setting::get('min_donate_da', '10000'),
            'min_donate_sa'        => Setting::get('min_donate_sa', '10000'),
            'min_donate_sp'        => Setting::get('min_donate_sp', '10000'),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request) {
        $request->validate([
            'duitku_merchant_code' => 'required|string',
            'duitku_api_key'       => 'required|string',
            'duitku_env'           => 'required|in:sandbox,production',
            'platform_fee_pct'     => 'required|numeric|min:0|max:20',
            'announcement'         => 'nullable|string|max:500',
        ]);

        Setting::set('duitku_merchant_code', $request->duitku_merchant_code);
        Setting::set('duitku_api_key',       $request->duitku_api_key);
        Setting::set('duitku_env',           $request->duitku_env);
        Setting::set('platform_fee_pct',     $request->platform_fee_pct);
        Setting::set('maintenance_mode',     $request->has('maintenance_mode') ? '1' : '0');
        Setting::set('announcement',         $request->announcement ?? '');
        Setting::set('payment_methods',      json_encode($request->payment_methods ?? []));
        Setting::set('min_donate_ov',        $request->min_donate_ov ?? '10000');
        Setting::set('min_donate_da',        $request->min_donate_da ?? '10000');
        Setting::set('min_donate_sa',        $request->min_donate_sa ?? '10000');
        Setting::set('min_donate_sp',        $request->min_donate_sp ?? '10000');

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
