@extends('dashboard')
@section('title', 'Edit Profil')

@section('content')

@php
  $nameParts = explode(' ', trim(Auth::user()->name));
  $initials = count($nameParts) >= 2
    ? strtoupper(substr($nameParts[0],0,1).substr($nameParts[1],0,1))
    : strtoupper(substr(Auth::user()->name,0,2));
@endphp

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/edit-profile.css') }}">

<div class="ep-wrap">

  {{-- ── Header ── --}}
  <div class="ep-header">
    <div class="ep-breadcrumb">
      <a href="{{ route('dashboard') }}">Dashboard</a>
      <span class="sep">/</span>
      <span class="active">Edit Profil</span>
    </div>
    <div class="ep-title">Edit Profil</div>
    <div class="ep-subtitle">Kelola informasi akun dan keamanan Anda</div>
    <div class="ep-edit-badge">
      <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
      Mode Edit — {{ Auth::user()->name }}
    </div>
  </div>

  {{-- ── Alert Success ── --}}
  @if(session('success'))
  <div class="ep-alert-ok">
    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
    {{ session('success') }}
  </div>
  @endif

  <div class="ep-grid-outer">

    {{-- ── Main Card ── --}}
    <div class="ep-card">
      <div class="ep-card-bar"></div>

      <div class="ep-card-head">
        <div class="ep-card-icon">
          <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
        </div>
        <div class="ep-card-head-text">
          <div class="cht">Informasi Akun</div>
          <div class="chs">Perbarui nama dan email Anda</div>
        </div>
        <div class="ep-role-chip">
          <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          {{ $user->role ?? 'Administrator' }}
        </div>
      </div>

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="ep-form-body">

          {{-- ── Avatar Preview ── --}}
          <div class="ep-avatar-wrap">
            <div class="ep-avatar" id="avatarCircle">{{ $initials }}</div>
            <div>
              <div class="ep-avatar-name" id="avatarName">{{ Auth::user()->name }}</div>
              <div class="ep-avatar-role">{{ $user->role ?? 'Administrator' }} · 5A Auto Service</div>
            </div>
          </div>

          {{-- ── Identitas ── --}}
          <div class="ep-section-label">Identitas</div>

          <div class="ep-form-row">

            {{-- Nama --}}
            <div class="mz-field">
              <label class="mz-label">Nama Lengkap</label>
              <div class="mz-input-wrap">
                <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <input type="text" name="name" id="nameInput"
                       class="mz-input @error('name') is-error @enderror"
                       value="{{ old('name', $user->name) }}"
                       data-original="{{ $user->name }}"
                       oninput="markChanged(this); updateAvatar()"
                       placeholder="Nama lengkap"
                       required>
              </div>
              @error('name')<span class="mz-error">{{ $message }}</span>@enderror
            </div>

            {{-- Email --}}
            <div class="mz-field">
              <label class="mz-label">Alamat Email</label>
              <div class="mz-input-wrap">
                <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                <input type="email" name="email"
                       class="mz-input @error('email') is-error @enderror"
                       value="{{ old('email', $user->email) }}"
                       data-original="{{ $user->email }}"
                       oninput="markChanged(this)"
                       placeholder="email@domain.com"
                       required>
              </div>
              @error('email')<span class="mz-error">{{ $message }}</span>@enderror
            </div>

          </div>

          {{-- Role read-only --}}
          <div class="mz-field" style="margin-top: 4px">
            <label class="mz-label">Role</label>
            <div class="mz-input-wrap">
              <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
              <input type="text" class="mz-input mz-input-readonly"
                     value="{{ $user->role ?? 'Administrator' }}" readonly>
            </div>
            <div class="mz-hint">Role tidak dapat diubah melalui halaman ini</div>
          </div>

          <hr class="ep-sep">

          {{-- ── Password ── --}}
          <div class="ep-section-label">Ganti Password</div>
          <p class="mz-hint" style="margin-bottom: 14px; margin-top: -6px">
            Kosongkan semua field password jika tidak ingin menggantinya
          </p>

          <div class="mz-field" style="margin-bottom: 14px">
            <label class="mz-label">Password Lama</label>
            <div class="mz-input-wrap">
              <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
              <input type="password" name="current_password" id="curPw"
                     class="mz-input mz-input-pw @error('current_password') is-error @enderror"
                     placeholder="Masukkan password lama">
              <button type="button" class="pw-toggle" onclick="epToggle('curPw', this)">
                <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
              </button>
            </div>
            @error('current_password')<span class="mz-error">{{ $message }}</span>@enderror
          </div>

          <div class="ep-form-row">

            <div class="mz-field">
              <label class="mz-label">Password Baru</label>
              <div class="mz-input-wrap">
                <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                <input type="password" name="password" id="newPw"
                       class="mz-input mz-input-pw @error('password') is-error @enderror"
                       placeholder="Min. 8 karakter"
                       oninput="epStrength(this.value)">
                <button type="button" class="pw-toggle" onclick="epToggle('newPw', this)">
                  <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                </button>
              </div>
              <div class="ep-strength" id="epBar">
                <span id="ep1"></span><span id="ep2"></span>
                <span id="ep3"></span><span id="ep4"></span>
              </div>
              <div class="strength-label" id="strengthLabel"></div>
              @error('password')<span class="mz-error">{{ $message }}</span>@enderror
            </div>

            <div class="mz-field">
              <label class="mz-label">Konfirmasi Password Baru</label>
              <div class="mz-input-wrap">
                <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                <input type="password" name="password_confirmation" id="confPw"
                       class="mz-input mz-input-pw"
                       placeholder="Ulangi password baru">
                <button type="button" class="pw-toggle" onclick="epToggle('confPw', this)">
                  <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                </button>
              </div>
            </div>

          </div>

        </div>{{-- /ep-form-body --}}

        {{-- ── Footer ── --}}
        <div class="ep-footer">
          <div class="ep-footer-hint">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            Perubahan akan langsung tersimpan ke database
          </div>
          <div class="ep-actions">
            <a href="{{ url()->previous() }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
              <svg viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
              Simpan Perubahan
            </button>
          </div>
        </div>

      </form>
    </div>{{-- /ep-card --}}

    {{-- ── Side Info Card ── --}}
    <div class="ep-side-card">
      <div class="ep-card-bar"></div>
      <div class="ep-card-head" style="padding: 14px 18px">
        <div class="ep-card-icon ep-card-icon--teal">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
        </div>
        <div class="ep-card-head-text">
          <div class="cht">Info Akun</div>
          <div class="chs">Detail &amp; statistik</div>
        </div>
      </div>

      <div class="ep-meta-list">
        <div class="ep-meta-row">
          <div class="ep-meta-label">Status</div>
          <div class="ep-meta-val ep-status">
            <span class="ep-dot"></span> Aktif
          </div>
        </div>
        <div class="ep-meta-row">
          <div class="ep-meta-label">Role</div>
          <div class="ep-meta-val">{{ $user->role ?? 'Administrator' }}</div>
        </div>
        <div class="ep-meta-row">
          <div class="ep-meta-label">Email</div>
          <div class="ep-meta-val">{{ $user->email }}</div>
        </div>
        <div class="ep-meta-row">
          <div class="ep-meta-label">Bergabung Sejak</div>
          <div class="ep-meta-val">{{ $user->created_at->format('d M Y') }}</div>
        </div>
        <div class="ep-meta-row">
          <div class="ep-meta-label">Login Terakhir</div>
          <div class="ep-meta-val">{{ now()->format('d M Y, H:i') }}</div>
        </div>
      </div>

    </div>{{-- /ep-side-card --}}

  </div>{{-- /ep-grid-outer --}}
</div>{{-- /ep-wrap --}}

<script>
function epToggle(id, btn) {
  const el = document.getElementById(id);
  const isText = el.type === 'text';
  el.type = isText ? 'password' : 'text';
  btn.style.color = isText ? '' : 'var(--mz-orange)';
}

function epStrength(v) {
  const checks = [
    v.length >= 8,
    /[A-Z]/.test(v),
    /[0-9]/.test(v),
    /[^A-Za-z0-9]/.test(v)
  ];
  const score = checks.filter(Boolean).length;
  const colors = ['', '#e24b4a', '#d85a30', '#ba7517', '#1d9e75'];
  const labels = ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'];
  for (let i = 1; i <= 4; i++) {
    document.getElementById('ep' + i).style.background =
      i <= score ? colors[score] : 'var(--mz-border)';
  }
  const lbl = document.getElementById('strengthLabel');
  lbl.textContent = v.length ? labels[score] : '';
  lbl.style.color = colors[score];
}

function markChanged(el) {
  el.classList.toggle('is-changed', el.value !== el.dataset.original);
}

function updateAvatar() {
  const name = document.getElementById('nameInput').value.trim();
  const parts = name.split(' ').filter(Boolean);
  const initials = parts.length >= 2
    ? (parts[0][0] + parts[1][0]).toUpperCase()
    : name.slice(0, 2).toUpperCase();
  document.getElementById('avatarCircle').textContent = initials || '??';
  document.getElementById('avatarName').textContent = name || '—';
}
</script>

@endsection
