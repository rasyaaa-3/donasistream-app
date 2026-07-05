@extends('layouts.app')

@section('title', 'Overlay')

@section('nav_overlay', 'active')

@section('styles')
<style>
    /* Notification box - stays INSIDE preview */
    .overlay-preview {
        background: var(--dark3);
        border: 1px solid var(--border);
        border-radius: 16px;
        aspect-ratio: 16/9;
        position: relative;
        overflow: hidden;
        min-height: 240px;
    }
    .overlay-preview .screen-label {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        font-size: 13px; font-weight: 600; color: var(--muted);
        text-align: center; pointer-events: none;
    }
    .overlay-preview .screen-label i { font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.3; }

    .notif-box {
        position: absolute;
        background: rgba(13,17,23,0.92);
        border: 1px solid;
        border-radius: 12px;
        padding: 12px 16px;
        min-width: 180px;
        max-width: 220px;
        backdrop-filter: blur(8px);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        /* Constrain inside preview via inset approach */
        box-sizing: border-box;
    }
    .notif-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--sky); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: var(--dark); flex-shrink: 0; }
    .notif-body { flex: 1; overflow: hidden; min-width: 0; }
    .notif-label { font-size: 9px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px; }
    .notif-name { font-size: 13px; font-weight: 800; color: white; }
    .notif-amount { font-size: 11px; font-weight: 700; margin-top: 2px; }
    .notif-msg { font-size: 10px; color: var(--muted2); margin-top: 2px; font-style: italic; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .notif-status { font-size: 9px; color: var(--muted); margin-top: 2px; }

    /* 3x3 position grid */
    .pos-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
    .pos-btn { padding: 10px 6px; border: 1px solid var(--border); border-radius: 10px; background: var(--dark3); color: var(--muted2); font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-family: inherit; display: flex; align-items: center; gap: 5px; justify-content: center; }
    .pos-btn:hover, .pos-btn.selected { background: var(--sky-dim); border-color: rgba(77,200,240,0.3); color: var(--sky); }

    .color-grid { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .color-swatch { width: 32px; height: 32px; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; flex-shrink: 0; }
    .color-swatch:hover, .color-swatch.selected { border-color: white; transform: scale(1.1); }
    .section-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; margin-bottom: 20px; }
    .section-head { padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
    .section-head h3 { font-size: 14px; font-weight: 700; color: white; }
    .section-head i { font-size: 16px; color: var(--sky); }
    .section-body { padding: 24px; }
    .slider-wrap { display: flex; align-items: center; gap: 14px; }
    .slider-wrap input[type=range] { flex: 1; accent-color: var(--sky); }
    .slider-val { font-size: 15px; font-weight: 800; color: var(--sky); min-width: 50px; text-align: right; }
    .url-box { display: flex; gap: 10px; align-items: center; background: var(--dark3); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; }
    .url-box span { flex: 1; font-size: 13px; color: var(--muted2); font-family: monospace; word-break: break-all; }
    .nama-akun-badge { display: flex; align-items: center; gap: 8px; padding: 12px 16px; background: var(--dark3); border: 1px solid var(--border); border-radius: 10px; }
    .nama-akun-badge i { color: var(--sky); font-size: 18px; }
    .nama-akun-badge span { font-size: 14px; font-weight: 700; color: white; }
</style>
@endsection

@section('content')
<div class="topbar">
    <div class="topbar-left">
        <p class="page-title">Pengaturan Overlay</p>
        <p class="page-sub">#kustom notifikasi donasi di OBS</p>
    </div>
</div>

<div class="page-content">
    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="grid-2" style="align-items:start; gap:24px">
        {{-- Left: Settings --}}
        <div>
            <form method="POST" action="{{ route('overlay.update') }}">
                @csrf

                {{-- Posisi (3x3 grid) --}}
                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-arrows-move"></i>
                        <h3>Posisi Overlay</h3>
                    </div>
                    <div class="section-body">
                        <input type="hidden" name="posisi" id="posisiInput" value="{{ $overlay->posisi }}">
                        <div class="pos-grid">
                            @php
                            $positions = [
                                'top-left'      => ['bi-arrow-up-left',   'Kiri Atas'],
                                'top-center'    => ['bi-arrow-up',        'Tengah Atas'],
                                'top-right'     => ['bi-arrow-up-right',  'Kanan Atas'],
                                'mid-left'      => ['bi-arrow-left',      'Kiri Tengah'],
                                'center'        => ['bi-fullscreen-exit', 'Tengah'],
                                'mid-right'     => ['bi-arrow-right',     'Kanan Tengah'],
                                'bottom-left'   => ['bi-arrow-down-left', 'Kiri Bawah'],
                                'bottom-center' => ['bi-arrow-down',      'Tengah Bawah'],
                                'bottom-right'  => ['bi-arrow-down-right','Kanan Bawah'],
                            ];
                            @endphp
                            @foreach($positions as $val => $item)
                            <button type="button"
                                class="pos-btn {{ $overlay->posisi === $val ? 'selected' : '' }}"
                                data-pos="{{ $val }}"
                                onclick="setPos('{{ $val }}', this)">
                                <i class="bi {{ $item[0] }}"></i> {{ $item[1] }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Nama Akun --}}
                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-person-badge-fill"></i>
                        <h3>Nama Tampilan</h3>
                    </div>
                    <div class="section-body">
                        <div class="nama-akun-badge">
                            <i class="bi bi-broadcast"></i>
                            <span>{{ auth()->user()->nama }}</span>
                        </div>
                        <p style="font-size:11px; color:var(--muted); margin-top:10px">
                            <i class="bi bi-info-circle"></i> Nama diambil dari profil akun kamu
                        </p>
                    </div>
                </div>

                {{-- Warna --}}
                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-palette-fill"></i>
                        <h3>Warna Aksen</h3>
                    </div>
                    <div class="section-body">
                        <div class="color-grid">
                            @foreach(['#4DC8F0','#4ade80','#f0d54d','#f0914d','#ef4444','#a855f7','#ec4899','#ffffff'] as $clr)
                            <div class="color-swatch {{ $overlay->warna === $clr ? 'selected' : '' }}"
                                style="background:{{ $clr }}"
                                onclick="setColor('{{ $clr }}', this)"></div>
                            @endforeach
                            <input type="color" id="customColor" value="{{ $overlay->warna }}"
                                oninput="setColor(this.value, null)"
                                style="width:32px; height:32px; border-radius:8px; border:2px solid var(--border); cursor:pointer; background:none; padding:0">
                        </div>
                        <input type="hidden" name="warna" id="warnaInput" value="{{ $overlay->warna }}">
                        <p style="font-size:11px; color:var(--muted); margin-top:10px">Klik kotak warna atau gunakan color picker</p>
                    </div>
                </div>

                {{-- Durasi --}}
                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-clock-fill"></i>
                        <h3>Durasi Tampil</h3>
                    </div>
                    <div class="section-body">
                        <div class="slider-wrap">
                            <input type="range" name="durasi" id="durasiSlider"
                                min="3" max="60" step="1" value="{{ $overlay->durasi }}"
                                oninput="document.getElementById('durasiVal').textContent = this.value + 's'">
                            <span class="slider-val" id="durasiVal">{{ $overlay->durasi }}s</span>
                        </div>
                        <p style="font-size:11px; color:var(--muted); margin-top:10px">
                            Notifikasi akan tampil selama <strong style="color:var(--text)" id="durasiDesc">{{ $overlay->durasi }}</strong> detik
                        </p>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:13px">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Right: Preview + OBS URL --}}
        <div>
            <div class="section-card">
                <div class="section-head">
                    <i class="bi bi-eye-fill"></i>
                    <h3>Preview Overlay</h3>
                </div>
                <div class="section-body">
                    <div class="overlay-preview" id="previewBox">
                        <div class="screen-label">
                            <i class="bi bi-display"></i>
                            Area Streaming
                        </div>
                        <div class="notif-box" id="notifBox"
                            style="border-color:{{ $overlay->warna }}40; box-shadow:0 4px 24px {{ $overlay->warna }}30">
                            <div class="notif-avatar" id="notifAvatar" style="background:{{ $overlay->warna }}">
                                {{ auth()->user()->initials }}
                            </div>
                            <div class="notif-body">
                                <p class="notif-label" id="notifLabel" style="color:{{ $overlay->warna }}">🎉 Donasi Masuk!</p>
                                <p class="notif-name">Wahyu</p>
                                <p class="notif-amount" id="notifAmount" style="color:{{ $overlay->warna }}">Rp 50.000</p>
                                <p class="notif-msg">"Halo Kak, terimakasih streamnya!"</p>
                                <p class="notif-status">Kepada: <strong style="color:var(--muted2)">{{ auth()->user()->nama }}</strong></p>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:11px; color:var(--muted); margin-top:12px; text-align:center">
                        Preview langsung berubah sesuai pengaturan
                    </p>
                </div>
            </div>

            {{-- OBS URL --}}
            <div class="section-card">
                <div class="section-head">
                    <i class="bi bi-camera-video-fill"></i>
                    <h3>URL untuk OBS</h3>
                </div>
                <div class="section-body">
                    <p style="font-size:13px; color:var(--muted2); margin-bottom:14px">
                        Tambahkan sebagai <strong style="color:var(--text)">Browser Source</strong> di OBS.
                        Ukuran canvas: <strong style="color:var(--text)">1920 × 1080</strong>.
                    </p>
                    <div class="url-box" style="margin-bottom:10px">
                        <span id="obsUrl" style="word-break:break-all; font-size:12px">{{ route('overlay.display', auth()->user()->overlay_token) }}</span>
                        <button onclick="copyObs()" class="btn btn-ghost" style="padding:8px 14px; font-size:12px; white-space:nowrap">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:8px">
                        <i class="bi bi-shield-lock" style="color:var(--muted); font-size:13px"></i>
                        <span style="font-size:11px; color:var(--muted)">URL mengandung token rahasia. Jangan bagikan ke publik.</span>
                        <button onclick="regenerateToken()" id="regenBtn" class="btn btn-ghost" style="padding:6px 12px; font-size:11px; margin-left:auto; white-space:nowrap">
                            <i class="bi bi-arrow-clockwise"></i> Perbarui Token
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const notif    = document.getElementById('notifBox');
const labelEl  = document.getElementById('notifLabel');
const amountEl = document.getElementById('notifAmount');
const avatarEl = document.getElementById('notifAvatar');
const preview  = document.getElementById('previewBox');

// Position map — percentages to keep notif inside the preview box
// We use % based values instead of fixed px so it scales with preview size
const posMap = {
    'top-left':      { top:'5%',   left:'3%',   transform:'' },
    'top-center':    { top:'5%',   left:'50%',  transform:'translateX(-50%)' },
    'top-right':     { top:'5%',   left:'auto', right:'3%', transform:'' },
    'mid-left':      { top:'50%',  left:'3%',   transform:'translateY(-50%)' },
    'center':        { top:'50%',  left:'50%',  transform:'translate(-50%,-50%)' },
    'mid-right':     { top:'50%',  left:'auto', right:'3%', transform:'translateY(-50%)' },
    'bottom-left':   { top:'auto', bottom:'5%', left:'3%',  transform:'' },
    'bottom-center': { top:'auto', bottom:'5%', left:'50%', transform:'translateX(-50%)' },
    'bottom-right':  { top:'auto', bottom:'5%', left:'auto',right:'3%', transform:'' },
};

function applyPos(val) {
    const p = posMap[val] || posMap['top-left'];
    notif.style.top       = p.top       || 'auto';
    notif.style.bottom    = p.bottom    || 'auto';
    notif.style.left      = p.left      || 'auto';
    notif.style.right     = p.right     || 'auto';
    notif.style.transform = p.transform || '';
}

function setPos(val, btn) {
    document.getElementById('posisiInput').value = val;
    document.querySelectorAll('.pos-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    applyPos(val);
}

function setColor(val, swatch) {
    document.getElementById('warnaInput').value = val;
    document.getElementById('customColor').value = val;
    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
    if (swatch) swatch.classList.add('selected');
    notif.style.borderColor    = val + '60';
    notif.style.boxShadow      = '0 4px 24px ' + val + '40';
    labelEl.style.color        = val;
    amountEl.style.color       = val;
    avatarEl.style.background  = val;
}

document.getElementById('durasiSlider').addEventListener('input', function() {
    document.getElementById('durasiDesc').textContent = this.value;
});

// Init position
applyPos('{{ $overlay->posisi }}');

function copyObs() {
    const url = document.getElementById('obsUrl').textContent.trim();
    navigator.clipboard.writeText(url).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}

async function regenerateToken() {
    if (!confirm('URL OBS lama akan tidak berlaku. Lanjutkan?')) return;
    const btn = document.getElementById('regenBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass"></i> Memperbarui...';
    try {
        const res  = await fetch('{{ route("overlay.regenerate_token") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        document.getElementById('obsUrl').textContent = data.url;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Diperbarui!';
        setTimeout(() => { btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Perbarui Token'; btn.disabled = false; }, 2500);
    } catch(e) {
        btn.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Gagal';
        btn.disabled = false;
    }
}
</script>
@endsection
