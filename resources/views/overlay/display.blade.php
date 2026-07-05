<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Overlay – {{ $user->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width:100%; height:100%; background:transparent !important; overflow:hidden; font-family:'Segoe UI',system-ui,sans-serif; }
        :root { --accent-color: {{ $overlay->warna ?? '#4DC8F0' }}; }
        #overlay-container { position:fixed; inset:0; pointer-events:none; }
        #notif {
            position:absolute;
            display:flex; align-items:center; gap:12px;
            background:rgba(10,14,20,0.90);
            border:1.5px solid var(--accent-color);
            border-radius:14px; padding:14px 18px;
            min-width:240px; max-width:320px;
            backdrop-filter:blur(12px);
            box-shadow:0 4px 24px rgba(0,0,0,0.5);
            @php $pos = $overlay->posisi ?? 'bottom-left'; @endphp
            @if($pos==='bottom-left') bottom:24px; left:24px;
            @elseif($pos==='bottom-right') bottom:24px; right:24px;
            @elseif($pos==='top-left') top:24px; left:24px;
            @elseif($pos==='top-right') top:24px; right:24px;
            @endif
            opacity:0; transform:translateY(20px) scale(0.95);
            transition:opacity 0.4s ease, transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }
        #notif.show { opacity:1; transform:translateY(0) scale(1); }
        #notif.hide { opacity:0; transform:translateY(-10px) scale(0.97); transition:opacity 0.35s ease,transform 0.35s ease; }
        .notif-avatar { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; color:#0a0e14; flex-shrink:0; }
        .notif-body { flex:1; overflow:hidden; }
        .notif-label { font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--accent-color); margin-bottom:3px; }
        .notif-name { font-size:15px; font-weight:800; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .notif-amount { font-size:13px; font-weight:700; color:var(--accent-color); margin-top:1px; }
        .notif-msg { font-size:11px; color:rgba(255,255,255,0.55); margin-top:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-style:italic; }
        .notif-bar { position:absolute; bottom:0; left:0; height:3px; background:var(--accent-color); border-radius:0 0 14px 14px; width:100%; transform-origin:left; opacity:0.7; }
        .notif-bar.draining { transition:transform linear; }
    </style>
</head>
<body>
<div id="overlay-container">
    <div id="notif">
        <div class="notif-avatar" id="notif-avatar"></div>
        <div class="notif-body">
            <div class="notif-label">💝 Donasi Masuk</div>
            <div class="notif-name" id="notif-name">—</div>
            <div class="notif-amount" id="notif-amount">—</div>
            <div class="notif-msg" id="notif-msg"></div>
        </div>
        <div class="notif-bar" id="notif-bar"></div>
    </div>
</div>
<script>
    const POLL_URL  = '{{ route("overlay.poll", $user->overlay_token) }}';
    const DURASI_MS = {{ ($overlay->durasi ?? 10) * 1000 }};
    let lastTs = Math.floor(Date.now()/1000) - 5;
    let showing = false, queue = [];

    async function pollDonasi() {
        try {
            const res  = await fetch(`${POLL_URL}?since=${lastTs}`);
            const data = await res.json();
            if (data.donasi && data.donasi.created_at_ts > lastTs) {
                lastTs = data.donasi.created_at_ts;
                queue.push(data.donasi);
                if (!showing) showNext();
            }
        } catch(e) {}
    }

    function showNext() {
        if (!queue.length) { showing = false; return; }
        showing = true;
        showNotif(queue.shift());
    }

    function showNotif(d) {
        const notif  = document.getElementById('notif');
        const bar    = document.getElementById('notif-bar');
        document.getElementById('notif-avatar').textContent    = d.donor_initial || '?';
        document.getElementById('notif-avatar').style.background = d.donor_color || '#4DC8F0';
        document.getElementById('notif-name').textContent      = d.donor_nama;
        document.getElementById('notif-amount').textContent    = d.jumlah_format;
        document.getElementById('notif-msg').textContent       = d.pesan ? `"${d.pesan}"` : '';
        bar.style.transition = 'none';
        bar.style.transform  = 'scaleX(1)';
        notif.classList.remove('hide');
        requestAnimationFrame(() => {
            notif.classList.add('show');
            requestAnimationFrame(() => {
                bar.classList.add('draining');
                bar.style.transition = `transform ${DURASI_MS}ms linear`;
                bar.style.transform  = 'scaleX(0)';
            });
        });
        setTimeout(() => {
            notif.classList.remove('show');
            notif.classList.add('hide');
            bar.classList.remove('draining');
            setTimeout(() => { notif.classList.remove('hide'); showNext(); }, 500);
        }, DURASI_MS);
    }

    pollDonasi();
    setInterval(pollDonasi, 4000);
</script>
</body>
</html>
