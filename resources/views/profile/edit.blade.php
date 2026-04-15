@extends('dashboard')
@section('title', 'Edit Profil')

@section('content')

@php
  $nameParts = explode(' ', trim(Auth::user()->name));
  $initials = count($nameParts) >= 2
    ? strtoupper(substr($nameParts[0],0,1).substr($nameParts[1],0,1))
    : strtoupper(substr(Auth::user()->name,0,2));
@endphp

<style>

:root {
    --sidebar-w: 240px;
}

[data-theme="dark"] {
    --bg: #0f1117;
    --surface: #181c27;
    --surface2: #1e2333;
    --border: #262c3d;
    --accent: #4f8ef7;
    --accent2: #1e90ff;
    --text: #e4e8f0;
    --text-soft: #b0b8d0;
    --muted: #6b7694;
    --red: #f26c6c;
    --green: #3ef08a;
    --sidebar-surface: #181c27;
    --sidebar-border: #262c3d;
    --sidebar-text: #c8d0e8;
    --sidebar-muted: #6b7694;
    --sidebar-active-bg: rgba(79, 142, 247, 0.15);
    --sidebar-active-border: #4f8ef7;
    --sidebar-hover-bg: rgba(255, 255, 255, 0.05);
    --logo-filter: none;
}

[data-theme="light"] {
    --bg: #f0f4f8;
    --surface: #ffffff;
    --surface2: #f5f7fa;
    --border: #dde3ed;
    --accent: #2563eb;
    --accent2: #1d4ed8;
    --text: #1a202c;
    --text-soft: #4a5568;
    --muted: #718096;
    --red: #e53e3e;
    --green: #38a169;
    --sidebar-surface: #1b2a3b;
    --sidebar-border: rgba(255, 255, 255, 0.1);
    --sidebar-text: #cbd5e1;
    --sidebar-muted: #94a3b8;
    --sidebar-active-bg: rgba(255, 255, 255, 0.15);
    --sidebar-active-border: #ffffff;
    --sidebar-hover-bg: rgba(255, 255, 255, 0.07);
    --logo-filter: none;
}
.ep-breadcrumb{font-size:.75rem;color:var(--text-muted,#64748b);margin-bottom:.4rem;display:flex;align-items:center;gap:.4rem}
.ep-breadcrumb span{color:var(--accent,#f97316)}
.ep-title{font-family:'Rajdhani',sans-serif;font-size:1.75rem;font-weight:700;letter-spacing:.03em;color:var(--text-primary,#f1f5f9);margin-bottom:2rem}
.ep-grid{display:grid;grid-template-columns:1fr 260px;gap:1.25rem;align-items:start}
@media(max-width:680px){.ep-grid{grid-template-columns:1fr}}

.ep-card{background:var(--card-bg,#1c2333);border:1px solid var(--border,rgba(255,255,255,.07));border-radius:12px;padding:1.5rem}
.ep-card-title{font-family:'Rajdhani',sans-serif;font-size:1rem;font-weight:600;letter-spacing:.05em;color:var(--text-primary,#fff);display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border,rgba(255,255,255,.07))}
.ep-card-title svg{width:18px;height:18px;fill:var(--accent,#f97316);flex-shrink:0}

.ep-avatar-wrap{display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;padding:1rem;background:rgba(249,115,22,.1);border-radius:10px;border:1px solid rgba(249,115,22,.25)}
.ep-avatar{width:52px;height:52px;border-radius:50%;background:var(--accent,#f97316);display:flex;align-items:center;justify-content:center;font-family:'Rajdhani',sans-serif;font-size:1.25rem;font-weight:700;color:#fff;flex-shrink:0;border:2px solid rgba(249,115,22,.5)}
.ep-avatar-name{font-weight:500;font-size:.9rem;color:var(--text-primary,#fff)}
.ep-avatar-role{font-size:.75rem;color:var(--accent,#f97316);margin-top:2px}

.ep-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
@media(max-width:480px){.ep-row{grid-template-columns:1fr}}
.ep-fg{margin-bottom:1.1rem}
.ep-label{display:block;font-size:.72rem;font-weight:500;color:var(--text-muted,#64748b);margin-bottom:.35rem;letter-spacing:.05em;text-transform:uppercase}
.ep-input{width:100%;background:rgba(0,0,0,.25);border:1px solid var(--border,rgba(255,255,255,.1));border-radius:8px;padding:.6rem .85rem;color:var(--text-primary,#f1f5f9);font-size:.875rem;transition:border-color .2s,box-shadow .2s;outline:none;box-sizing:border-box}
.ep-input:focus{border-color:var(--accent,#f97316);box-shadow:0 0 0 3px rgba(249,115,22,.12)}
.ep-input.is-error{border-color:#ef4444}
.ep-error{font-size:.72rem;color:#ef4444;margin-top:.3rem;display:block}

.ep-divider{border:none;border-top:1px solid var(--border,rgba(255,255,255,.07));margin:1.25rem 0}
.ep-section{font-size:.7rem;font-weight:600;color:var(--text-muted,#64748b);letter-spacing:.08em;text-transform:uppercase;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.ep-section::after{content:'';flex:1;height:1px;background:var(--border,rgba(255,255,255,.07))}

.ep-pw-wrap{position:relative}
.ep-pw-toggle{position:absolute;right:.7rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:.2rem;color:var(--text-muted,#64748b)}
.ep-pw-toggle svg{width:16px;height:16px;fill:currentColor;display:block}
.ep-pw-toggle:hover{color:var(--text-primary,#f1f5f9)}

.ep-strength{display:flex;gap:4px;margin-top:6px}
.ep-strength span{height:3px;flex:1;border-radius:2px;background:rgba(255,255,255,.08);transition:background .3s}

.ep-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1.5rem;background:var(--accent,#f97316);color:#fff;border:none;border-radius:8px;font-size:.875rem;font-weight:500;cursor:pointer;transition:all .2s;margin-top:.5rem;letter-spacing:.02em}
.ep-btn:hover{opacity:.88;transform:translateY(-1px)}
.ep-btn svg{width:15px;height:15px;fill:#fff}

.ep-alert-ok{display:flex;align-items:center;gap:.5rem;background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.25);border-radius:8px;padding:.7rem 1rem;margin-bottom:1.5rem;font-size:.85rem}
.ep-alert-ok svg{width:16px;height:16px;fill:#10b981;flex-shrink:0}

.ep-meta-row{display:flex;flex-direction:column;gap:.15rem;padding:.7rem 0;border-bottom:1px solid var(--border,rgba(255,255,255,.07))}
.ep-meta-row:last-child{border-bottom:none}
.ep-meta-label{font-size:.7rem;color:var(--text-muted,#64748b);text-transform:uppercase;letter-spacing:.05em}
.ep-meta-val{font-size:.8rem;color:var(--text-primary,#f1f5f9);font-weight:500;margin-top:2px}
.ep-status{display:flex;align-items:center;gap:5px;color:#10b981}
.ep-dot{width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block}

.ep-stat-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:1.25rem}
.ep-stat{background:rgba(0,0,0,.2);border-radius:8px;padding:.75rem;text-align:center}
.ep-stat-num{font-family:'Rajdhani',sans-serif;font-size:1.5rem;font-weight:700;color:var(--accent,#f97316)}
.ep-stat-lbl{font-size:.65rem;color:var(--text-muted,#64748b);margin-top:2px;text-transform:uppercase;letter-spacing:.04em}
</style>

<div class="ep-breadcrumb">Dashboard <span>›</span> Edit Profil</div>
<div class="ep-title">Edit Profil</div>

@if(session('success'))
<div class="ep-alert-ok">
  <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
  {{ session('success') }}
</div>
@endif

<div class="ep-grid">
  <div class="ep-card">
    <div class="ep-card-title">
      <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
      Informasi Akun
    </div>

    <div class="ep-avatar-wrap">
      <div class="ep-avatar">{{ $initials }}</div>
      <div>
        <div class="ep-avatar-name">{{ Auth::user()->name }}</div>
        <div class="ep-avatar-role">Admin Portal · 5A Auto Service</div>
      </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST">
      @csrf @method('PUT')

      <div class="ep-row">
        <div class="ep-fg">
          <label class="ep-label">Nama Lengkap</label>
          <input type="text" name="name" class="ep-input @error('name') is-error @enderror"
                 value="{{ old('name', $user->name) }}" required>
          @error('name')<span class="ep-error">{{ $message }}</span>@enderror
        </div>
        <div class="ep-fg">
          <label class="ep-label">Username</label>
          <input type="text" name="username" class="ep-input @error('username') is-error @enderror"
                 value="{{ old('username', $user->username) }}" required>
          @error('username')<span class="ep-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="ep-divider"></div>
      <div class="ep-section">Ganti Password</div>

      <div class="ep-fg">
        <label class="ep-label">Password Lama</label>
        <div class="ep-pw-wrap">
          <input type="password" name="current_password" id="curPw"
                 class="ep-input @error('current_password') is-error @enderror"
                 placeholder="Isi jika ingin ganti password">
          <button type="button" class="ep-pw-toggle" onclick="epToggle('curPw')">
            <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
          </button>
        </div>
        @error('current_password')<span class="ep-error">{{ $message }}</span>@enderror
      </div>

      <div class="ep-row">
        <div class="ep-fg">
          <label class="ep-label">Password Baru</label>
          <div class="ep-pw-wrap">
            <input type="password" name="password" id="newPw"
                   class="ep-input @error('password') is-error @enderror"
                   placeholder="Min. 8 karakter" oninput="epStrength(this.value)">
            <button type="button" class="ep-pw-toggle" onclick="epToggle('newPw')">
              <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
            </button>
          </div>
          <div class="ep-strength" id="epBar">
            <span id="ep1"></span><span id="ep2"></span><span id="ep3"></span><span id="ep4"></span>
          </div>
          @error('password')<span class="ep-error">{{ $message }}</span>@enderror
        </div>
        <div class="ep-fg">
          <label class="ep-label">Konfirmasi Password</label>
          <div class="ep-pw-wrap">
            <input type="password" name="password_confirmation" id="confPw"
                   class="ep-input" placeholder="Ulangi password baru">
            <button type="button" class="ep-pw-toggle" onclick="epToggle('confPw')">
              <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
            </button>
          </div>
        </div>
      </div>

      <button type="submit" class="ep-btn">
        <svg viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
        Simpan Perubahan
      </button>
    </form>
  </div>

  <div class="ep-card">
    <div class="ep-card-title">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
      Info Akun
    </div>
    <div class="ep-meta-row">
      <div class="ep-meta-label">Status</div>
      <div class="ep-meta-val ep-status"><span class="ep-dot"></span> Aktif</div>
    </div>
    <div class="ep-meta-row">
      <div class="ep-meta-label">Role</div>
      <div class="ep-meta-val">{{ $user->role ?? 'Administrator' }}</div>
    </div>
    <div class="ep-meta-row">
      <div class="ep-meta-label">Bergabung Sejak</div>
      <div class="ep-meta-val">{{ $user->created_at->format('d M Y') }}</div>
    </div>
    <div class="ep-meta-row">
      <div class="ep-meta-label">Login Terakhir</div>
      <div class="ep-meta-val">{{ now()->format('d M Y, H:i') }}</div>
    </div>
    <div class="ep-stat-row">
      <div class="ep-stat">
        <div class="ep-stat-num">—</div>
        <div class="ep-stat-lbl">Invoice</div>
      </div>
      <div class="ep-stat">
        <div class="ep-stat-num">—</div>
        <div class="ep-stat-lbl">Bulan Ini</div>
      </div>
    </div>
  </div>
</div>

<script>
function epToggle(id){
  const i=document.getElementById(id);
  i.type=i.type==='password'?'text':'password';
}
function epStrength(v){
  const checks=[v.length>=8,/[A-Z]/.test(v),/[0-9]/.test(v),/[^A-Za-z0-9]/.test(v)];
  const score=checks.filter(Boolean).length;
  const c=['','#ef4444','#f97316','#eab308','#10b981'];
  for(let i=1;i<=4;i++)
    document.getElementById('ep'+i).style.background=i<=score?c[score]:'rgba(255,255,255,.08)';
}
</script>

@endsection
