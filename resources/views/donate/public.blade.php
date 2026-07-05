<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donasi untuk {{ $streamer->nama }} — DonasiStream</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sky:       #4DC8F0;
            --sky-dim:   rgba(77,200,240,0.12);
            --sky-glow:  rgba(77,200,240,0.25);
            --dark:      #0d1117;
            --dark2:     #161b27;
            --dark3:     #1e2540;
            --card:      #1a2035;
            --border:    rgba(255,255,255,0.06);
            --text:      #e2e8f4;
            --muted:     #6b7db3;
            --muted2:    #8a94b0;
        }
        html, body { min-height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--dark); color: var(--text); }
        a { text-decoration: none; color: inherit; }
        .page-wrap { max-width: 980px; margin: 0 auto; padding: 32px 20px 60px; }

        .streamer-head { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; }
        .streamer-avatar { width: 64px; height: 64px; border-radius: 50%; background: var(--sky); color: var(--dark); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 22px; flex-shrink: 0; }
        .streamer-name { font-size: 20px; font-weight: 800; color: white; }
        .streamer-handle { font-size: 13px; color: var(--muted2); }
        .streamer-bio { font-size: 13px; color: var(--muted2); margin-top: 6px; max-width: 520px; }

        .donate-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }
        @media (max-width: 800px) { .donate-grid { grid-template-columns: 1fr; } }

        .donate-form-card, .riwayat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .form-head, .card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; justify-content: space-between; }
        .form-head h3, .card-title { font-size: 14px; font-weight: 700; color: white; margin: 0; }
        .form-head i { font-size: 18px; color: var(--sky); }
        .form-body, .card-body { padding: 24px; }
        .card-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--muted2); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 12px; border-radius: 9px; border: 1px solid var(--border); background: var(--dark3); color: var(--text); font-size: 13px; font-family: inherit; }
        .form-control:focus { outline: none; border-color: var(--sky); }
        .invalid-feedback { display: block; color: #f87171; font-size: 11px; margin-top: 5px; }

        .nominal-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-bottom: 14px; }
        .nominal-btn { padding: 10px 6px; border: 1px solid var(--border); border-radius: 9px; background: var(--dark3); color: var(--muted2); font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s; font-family: inherit; text-align: center; }
        .nominal-btn:hover, .nominal-btn.selected { background: var(--sky-dim); border-color: rgba(77,200,240,0.3); color: var(--sky); }

        .amount-wrap { position: relative; }
        .amount-prefix { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 13px; font-weight: 700; color: var(--muted2); }
        .amount-input { padding-left: 40px !important; }

        .method-label { font-size: 12px; font-weight: 700; color: var(--muted2); text-transform: uppercase; letter-spacing: 0.5px; margin: 16px 0 8px; }
        .method-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .method-btn { padding: 10px 12px; border: 1px solid var(--border); border-radius: 9px; background: var(--dark3); color: var(--muted2); font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-family: inherit; text-align: left; }
        .method-btn:hover, .method-btn.selected { background: var(--sky-dim); border-color: rgba(77,200,240,0.35); color: var(--sky); }

        .char-counter { font-size: 11px; color: var(--muted); text-align: right; margin-top: 5px; }

        .btn-donate { width: 100%; padding: 13px; border-radius: 10px; background: var(--sky); color: var(--dark); font-size: 15px; font-weight: 800; border: none; cursor: pointer; transition: all 0.2s; font-family: inherit; box-shadow: 0 4px 20px var(--sky-glow); display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 16px; }
        .btn-donate:hover { background: #38b0d8; transform: translateY(-1px); }
        .btn-donate:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .success-banner { background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.2); border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; margin-bottom: 20px; }
        .success-icon { width: 44px; height: 44px; border-radius: 50%; background: rgba(74,222,128,0.15); display: flex; align-items: center; justify-content: center; font-size: 20px; color: #4ade80; flex-shrink: 0; }
        .success-title { font-size: 14px; font-weight: 700; color: #4ade80; }
        .success-sub { font-size: 12px; color: var(--muted2); margin-top: 2px; }

        .pending-banner { background: rgba(240,213,77,0.08); border: 1px solid rgba(240,213,77,0.2); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: #f0d54d; font-size: 13px; }
        .error-banner { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: #f87171; font-size: 13px; }

        .donor-row { display: flex; align-items: center; gap: 12px; padding: 13px 0; border-bottom: 1px solid var(--border); }
        .donor-row:last-child { border-bottom: none; padding-bottom: 0; }
        .donor-row:first-child { padding-top: 0; }
        .donor-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: #1a1a2e; flex-shrink: 0; }
        .donor-name { font-size: 13px; font-weight: 700; color: white; }
        .donor-time { font-size: 11px; color: var(--muted); margin-top: 2px; }
        .donor-msg { font-size: 12px; color: var(--muted2); margin-top: 3px; font-style: italic; }
        .donor-amount { font-size: 13px; font-weight: 800; color: var(--sky); margin-left: auto; white-space: nowrap; }
        .empty-state { text-align: center; padding: 40px 20px; color: var(--muted); }
        .empty-state i { font-size: 40px; opacity: 0.3; display: block; margin-bottom: 10px; }

        .sandbox-notice { background: rgba(240,213,77,0.08); border: 1px solid rgba(240,213,77,0.2); border-radius: 8px; padding: 10px 14px; font-size: 11px; color: #f0d54d; display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
        .footer-link { text-align: center; margin-top: 28px; font-size: 12px; color: var(--muted); }
        .footer-link a { color: var(--sky); font-weight: 700; }
    </style>
</head>
<body>
<div class="page-wrap">

    <div class="streamer-head">
        <div class="streamer-avatar">{{ $streamer->initials }}</div>
        <div>
            <div class="streamer-name">{{ $streamer->nama }}</div>
            <div class="streamer-handle">@{{ $streamer->username }}</div>
            @if($streamer->bio)
            <div class="streamer-bio">{{ $streamer->bio }}</div>
            @endif
        </div>
    </div>

    @if(session('donate_success'))
    <div class="success-banner">
        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
        <div>
            <div class="success-title">Pembayaran Berhasil! 🎉</div>
            <div class="success-sub">
                <strong style="color:white">{{ session('donate_nama') }}</strong>
                berhasil mengirim <strong style="color:var(--sky)">{{ session('donate_amount') }}</strong>
                ke {{ $streamer->nama }}
            </div>
        </div>
    </div>
    @endif

    @if(session('donate_pending'))
    <div class="pending-banner">
        <i class="bi bi-hourglass-split"></i>
        Pembayaran kamu sedang diproses. Halaman ini akan menampilkan donasimu begitu pembayaran dikonfirmasi.
    </div>
    @endif

    @if(session('error'))
    <div class="error-banner"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
    @endif

    @if($errors->any())
    <div class="error-banner"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    <div class="donate-grid">
        <div>
            <div class="donate-form-card">
                <div class="form-head">
                    <h3><i class="bi bi-heart-fill"></i> Kirim Donasi ke {{ $streamer->nama }}</h3>
                </div>
                <div class="form-body">

                    @if(($duitkuEnv ?? config('duitku.env')) === 'sandbox')
                    <div class="sandbox-notice">
                        <i class="bi bi-cone-striped"></i>
                        Mode <strong>Sandbox</strong> — transaksi tidak nyata.
                    </div>
                    @endif

                    <form method="POST" action="{{ route('donate.public.send', $streamer->username) }}" id="donateForm">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Nama Donatur</label>
                            <input type="text" name="donor_nama"
                                class="form-control @error('donor_nama') is-invalid @enderror"
                                placeholder="cth: Wahyu Ganteng"
                                value="{{ old('donor_nama') }}"
                                required maxlength="60">
                            @error('donor_nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nominal Donasi</label>
                            <div class="nominal-grid">
                                @foreach([10000,20000,50000,100000,250000,500000] as $nom)
                                <button type="button" class="nominal-btn" onclick="setNominal({{ $nom }}, this)">
                                    Rp {{ number_format($nom,0,',','.') }}
                                </button>
                                @endforeach
                            </div>
                            <div class="amount-wrap">
                                <span class="amount-prefix">Rp</span>
                                <input type="number" name="jumlah" id="jumlahInput"
                                    class="form-control amount-input @error('jumlah') is-invalid @enderror"
                                    placeholder="0" min="10000" max="10000000"
                                    value="{{ old('jumlah') }}" required>
                            </div>
                            <p style="font-size:11px; color:var(--muted); margin-top:6px">
                                <i class="bi bi-info-circle"></i> Minimal Rp 10.000 · Maksimal Rp 10.000.000
                            </p>
                            @error('jumlah')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ old('payment_method') }}">

                            @php $grouped = collect($paymentMethods)->groupBy('type'); @endphp
                            @foreach($grouped as $type => $methods)
                                <div class="method-label">{{ $type }}</div>
                                <div class="method-grid">
                                    @foreach($methods as $m)
                                    <button type="button" class="method-btn {{ old('payment_method') === $m['code'] ? 'selected' : '' }}"
                                        onclick="selectMethod('{{ $m['code'] }}', this)">
                                        <span>{{ $m['name'] }}</span>
                                    </button>
                                    @endforeach
                                </div>
                            @endforeach
                            @error('payment_method')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Pesan <span style="color:var(--muted); font-weight:400; text-transform:none">(opsional)</span></label>
                            <textarea name="pesan" id="pesanInput"
                                class="form-control @error('pesan') is-invalid @enderror"
                                placeholder="Tulis pesan untuk {{ $streamer->nama }}..."
                                maxlength="300"
                                style="min-height:90px">{{ old('pesan') }}</textarea>
                            <div class="char-counter" id="charCounter">0 / 300 karakter</div>
                            @error('pesan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="btn-donate" id="submitBtn">
                            <i class="bi bi-credit-card-fill"></i> Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="riwayat-card">
                <div class="card-header">
                    <div>
                        <p class="card-title">Donasi Terbaru</p>
                        <p class="card-sub">{{ $riwayat->count() }} donasi terbaru untuk {{ $streamer->nama }}</p>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($riwayat as $i => $d)
                    <div class="donor-row">
                        <div class="donor-avatar" style="background:{{ $d->donor_color }}">{{ $d->donor_initial }}</div>
                        <div style="flex:1; min-width:0">
                            <div style="display:flex; align-items:center">
                                <span class="donor-name">{{ $d->donor_nama }}</span>
                                @if($i === 0 && session('donate_success'))
                                <span style="margin-left:6px; font-size:9px; font-weight:700; color:#4ade80;">BARU</span>
                                @endif
                            </div>
                            <div class="donor-time">{{ $d->waktu }}</div>
                            @if($d->pesan)
                            <div class="donor-msg">"{{ Str::limit($d->pesan, 60) }}"</div>
                            @endif
                        </div>
                        <div class="donor-amount">{{ $d->jumlahFormat }}</div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Belum ada donasi. Jadilah yang pertama!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="footer-link">Didukung oleh <a href="{{ route('login') }}">DonasiStream</a></div>
</div>

<script>
function setNominal(val, btn) {
    document.getElementById('jumlahInput').value = val;
    document.querySelectorAll('.nominal-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
}
document.getElementById('jumlahInput')?.addEventListener('input', function() {
    document.querySelectorAll('.nominal-btn').forEach(b => {
        const nomVal = parseInt(b.textContent.replace(/\D/g,''));
        b.classList.toggle('selected', nomVal === parseInt(this.value));
    });
});
function selectMethod(code, btn) {
    document.getElementById('paymentMethodInput').value = code;
    document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
}
const pesanInput = document.getElementById('pesanInput');
const charCounter = document.getElementById('charCounter');
pesanInput?.addEventListener('input', function() {
    charCounter.textContent = this.value.length + ' / 300 karakter';
});
document.getElementById('donateForm')?.addEventListener('submit', function(e) {
    const method = document.getElementById('paymentMethodInput').value;
    if (!method) {
        e.preventDefault();
        alert('Pilih metode pembayaran terlebih dahulu.');
        return;
    }
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menghubungi Duitku...';
});
</script>
</body>
</html>
