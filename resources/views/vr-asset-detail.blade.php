@extends('layouts.app')

@section('title', $vrAsset->title . ' - VR Asset')
@section('page', 'vr')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $vrAsset->title }}</h1>
    <p>{{ $vrAsset->description }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('vr.assets') }}" class="btn btn-ghost btn-sm">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Back to Library
    </a>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
  <div>
    <div class="card">
      @php
        $imgMap = [
          1 => 'forest.svg',
          2 => 'ocean.svg',
          3 => 'mountain.svg',
          4 => 'breathing.svg',
          5 => 'starry.svg',
          6 => 'zen.svg',
        ];
        $imgFile = $vrAsset->image_path
          ? Storage::url($vrAsset->image_path)
          : (isset($imgMap[$vrAsset->id])
            ? asset('assets/images/vr/' . $imgMap[$vrAsset->id])
            : null);
      @endphp
      <div style="height: 220px; border-radius: var(--radius-2xl) var(--radius-2xl) 0 0; overflow: hidden; position: relative; @if(!$imgFile) background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); @endif">
        @if($imgFile)
          <img src="{{ $imgFile }}" alt="{{ $vrAsset->title }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
        @else
          <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));">🌿</span>
          </div>
        @endif
      </div>
      <div style="padding: 1.5rem;">
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
          <span class="badge badge--secondary">{{ $vrAsset->category }}</span>
          <span class="badge badge--neutral">{{ $vrAsset->duration_minutes ?? 10 }} min</span>
          @if($vrAsset->difficulty_level)
            <span class="badge badge--info">Difficulty: {{ $vrAsset->difficulty_level }}/5</span>
          @endif
          @if($vrAsset->file_type)
            <span class="badge badge--neutral">{{ ucfirst($vrAsset->file_type) }}</span>
          @endif
        </div>
        <p style="margin: 0 0 1.25rem; line-height: 1.6; color: var(--color-text-secondary);">{{ $vrAsset->description }}</p>
        <button class="btn btn-primary" style="width: 100%;" onclick="launchUnityVR({{ $vrAsset->id }})">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          Launch Experience
        </button>
      </div>
    </div>
  </div>
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Session Details</h4>
      </div>
      <div class="card-body">
        <div style="display: grid; gap: 0.75rem;">
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Category</span>
            <span style="font-weight: 600;">{{ $vrAsset->category }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Duration</span>
            <span style="font-weight: 600;">{{ $vrAsset->duration_minutes ?? 10 }} minutes</span>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Difficulty Level</span>
            <span style="font-weight: 600;">{{ $vrAsset->difficulty_level ?? 'N/A' }}/5</span>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">File Type</span>
            <span style="font-weight: 600;">{{ ucfirst($vrAsset->file_type ?? 'N/A') }}</span>
          </div>
          @if($vrAsset->average_rating)
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Rating</span>
            <span style="font-weight: 600;">{{ number_format($vrAsset->average_rating, 1) }} / 5</span>
          </div>
          @endif
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
            <span class="text-muted">Times Used</span>
            <span style="font-weight: 600;">{{ $vrAsset->usage_count ?? 0 }}</span>
          </div>
        </div>
      </div>
    </div>

    @if(is_array($vrAsset->therapeutic_benefits) && count($vrAsset->therapeutic_benefits) > 0)
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Therapeutic Benefits</h4>
      </div>
      <div class="card-body">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
          @foreach($vrAsset->therapeutic_benefits as $benefit)
            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <span style="font-size: 0.9rem;">{{ $benefit }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <div class="card" style="margin-top: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">VR Setup</h4>
        <span class="badge badge--info">How-to</span>
      </div>
      <div class="card-body">
        <div style="display: grid; gap: 0.75rem;">
          <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-xl);">
            <div style="width: 36px; height: 36px; border-radius: var(--radius-lg); background: var(--color-primary-soft); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">1</div>
            <div>
              <p style="font-weight: 600; margin: 0 0 0.125rem;">Connect Your VR Headset</p>
              <p class="text-muted text-sm" style="margin: 0 0 0.375rem;">Ensure your VR headset is properly connected and powered on.</p>
              <span id="vr-assets-status-badge" class="badge badge--neutral" style="font-size:0.65rem;">Checking VR connection...</span>
            </div>
          </div>
          <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-xl);">
            <div style="width: 36px; height: 36px; border-radius: var(--radius-lg); background: var(--color-secondary-soft); color: var(--color-secondary); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">2</div>
            <div>
              <p style="font-weight: 600; margin: 0 0 0.125rem;">Enable WebXR</p>
              <p class="text-muted text-sm" style="margin: 0;">Make sure your browser supports WebXR and VR is enabled in settings.</p>
            </div>
          </div>
          <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-xl);">
            <div style="width: 36px; height: 36px; border-radius: var(--radius-lg); background: var(--color-accent-soft); color: var(--color-accent); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">3</div>
            <div>
              <p style="font-weight: 600; margin: 0 0 0.125rem;">Start Your Session</p>
              <p class="text-muted text-sm" style="margin: 0;">Click "Launch Experience" to begin your VR therapy session.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentSessionId = null;
let statusPollInterval = null;

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

async function launchUnityVR(assetId) {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;

    // Check VR headset connection before launching
    if (window.VRDetector) {
        const state = VRDetector.getState();
        if (state.status === 'not-connected' || state.status === 'unsupported') {
            showHeadsetWarning(state);
            return;
        }
        if (state.status === 'unknown') {
            try {
                const result = await VRDetector.detect();
                if (!result.connected) {
                    showHeadsetWarning(result);
                    return;
                }
            } catch (e) {
                // proceed anyway if detection fails
            }
        }
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Launching...';

    try {
        const moodResponse = await fetch('/api/user/current-mood', {
            headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' }
        });
        const moodData = await moodResponse.json();
        const moodBefore = moodData.mood ? moodData.mood.mood_scale : null;

        const response = await fetch('/api/vr/launch-unity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                vr_asset_id: assetId,
                mood_before: moodBefore
            })
        });

        const data = await response.json();

        if (!data.success) {
            alert('Failed to launch VR experience: ' + (data.error || 'Unknown error'));
            btn.disabled = false;
            btn.innerHTML = originalText;
            return;
        }

        currentSessionId = data.session.id;
        showUnityLaunchModal(data);
        startStatusPolling(data.session.id);
    } catch (error) {
        console.error('Error launching Unity VR:', error);
        alert('Error launching VR experience. Make sure the backend services are running.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

function showUnityLaunchModal(data) {
    const existing = document.getElementById('unity-launch-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'unity-launch-modal';
    modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;display:flex;align-items:center;justify-content:center;';

    var headsetState = window.VRDetector ? VRDetector.getState() : { status: 'unknown', headsetName: null };
    var headsetIcon = headsetState.connected ? '✅' : '⚠️';
    var headsetLabel = headsetState.connected
        ? (headsetState.headsetName || 'VR Headset') + ' Connected'
        : 'No VR Headset Detected';
    var headsetClass = headsetState.connected ? 'badge--success' : 'badge--warning';

    modal.innerHTML = `
        <div style="background:var(--color-surface);border-radius:var(--radius-2xl);padding:2rem;max-width:520px;width:90%;text-align:center;">
            <div style="font-size:3rem;margin-bottom:1rem;">🎮</div>
            <h3 style="margin:0 0 0.5rem;">Launching VR Experience</h3>
            <div id="headset-status-banner" style="margin-bottom:1.25rem;padding:0.75rem 1rem;border-radius:var(--radius-lg);background:${headsetState.connected ? 'rgba(34,197,94,0.1)' : 'rgba(250,204,21,0.1)'};border:1px solid ${headsetState.connected ? 'rgba(34,197,94,0.3)' : 'rgba(250,204,21,0.3)'};display:flex;align-items:center;gap:0.75rem;">
                <span style="font-size:1.5rem;">${headsetIcon}</span>
                <div style="flex:1;text-align:left;">
                    <p style="font-weight:600;margin:0;font-size:0.9rem;color:${headsetState.connected ? '#22c55e' : '#ca8a04'};">${headsetLabel}</p>
                    <p style="margin:0.125rem 0 0;font-size:0.8rem;color:var(--color-text-muted);">${headsetState.connected ? 'You are ready to start your session.' : 'Connect your VR headset via Link cable or Air Link.'}</p>
                </div>
            </div>
            <div id="launch-status" style="margin-bottom:1.5rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;justify-content:center;">
                    <div class="spinner" style="width:20px;height:20px;border:3px solid var(--color-border);border-top-color:var(--color-primary);border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                    <span id="status-text" style="font-weight:500;">Waiting for Unity connection...</span>
                </div>
            </div>
            <div id="session-info" style="display:none;margin-bottom:1.5rem;padding:1rem;background:var(--color-surface-muted);border-radius:var(--radius-xl);">
                <p style="margin:0 0 0.25rem;"><strong>Scene:</strong> <span id="scene-name">${data.session.vr_asset_title || 'Unknown'}</span></p>
                <p style="margin:0 0 0.25rem;"><strong>Duration:</strong> <span id="session-duration">${Math.floor(data.duration_seconds / 60)}:${(data.duration_seconds % 60).toString().padStart(2, '0')}</span></p>
                <p style="margin:0;"><strong>Timer:</strong> <span id="session-timer">00:00</span></p>
            </div>
            <div style="display:flex;gap:0.5rem;justify-content:center;">
                <button onclick="closeUnityModal()" class="btn btn-secondary btn-sm">Cancel</button>
                <button id="end-unity-session-btn" onclick="endUnitySession()" class="btn btn-danger btn-sm" style="display:none;background:#ef4444;color:#fff;">End Session</button>
            </div>
            <p style="color:var(--color-text-muted);font-size:0.8rem;margin:1rem 0 0;">
                Session ID: ${data.session.id}
            </p>
        </div>
    `;

    document.body.appendChild(modal);

    const style = document.createElement('style');
    style.id = 'unity-modal-styles';
    style.textContent = `
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-danger { background: #ef4444; color: #fff; border: none; }
        .btn-danger:hover { background: #dc2626; }
    `;
    document.head.appendChild(style);
}

function startStatusPolling(sessionId) {
    if (statusPollInterval) clearInterval(statusPollInterval);

    let startTime = Date.now();

    statusPollInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/vr/check-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ session_id: sessionId })
            });

            const data = await response.json();
            const statusText = document.getElementById('status-text');
            const sessionInfo = document.getElementById('session-info');
            const endBtn = document.getElementById('end-unity-session-btn');
            const timerEl = document.getElementById('session-timer');
            const launchStatus = document.getElementById('launch-status');

            if (data.is_completed) {
                clearInterval(statusPollInterval);
                statusPollInterval = null;
                if (statusText) statusText.textContent = 'Session completed.';
                if (endBtn) endBtn.style.display = 'none';
                return;
            }

            switch (data.unity_status) {
                case 'start':
                    if (statusText) statusText.textContent = 'Preparing VR environment...';
                    break;
                case 'running':
                    if (statusText) statusText.textContent = 'VR experience is active on your headset.';
                    if (sessionInfo) sessionInfo.style.display = 'block';
                    if (endBtn) endBtn.style.display = 'inline-flex';
                    if (launchStatus) {
                        const spinner = launchStatus.querySelector('.spinner');
                        if (spinner) {
                            spinner.style.borderTopColor = '#22c55e';
                            spinner.style.animation = 'none';
                            spinner.style.width = '12px';
                            spinner.style.height = '12px';
                            spinner.style.background = '#22c55e';
                            spinner.style.borderRadius = '50%';
                        }
                    }
                    if (timerEl) {
                        const elapsed = Math.floor((Date.now() - startTime) / 1000);
                        const m = Math.floor(elapsed / 60).toString().padStart(2, '0');
                        const s = (elapsed % 60).toString().padStart(2, '0');
                        timerEl.textContent = m + ':' + s;
                    }
                    break;
                case 'ended':
                    if (statusText) statusText.textContent = 'VR session ended.';
                    if (endBtn) endBtn.style.display = 'none';
                    clearInterval(statusPollInterval);
                    statusPollInterval = null;
                    break;
                case 'idle':
                    if (statusText) statusText.textContent = 'Waiting for Unity to connect...';
                    break;
                case 'backend-unreachable':
                    if (statusText) statusText.textContent = 'Connecting to backend service...';
                    break;
                default:
                    if (statusText) statusText.textContent = 'Status: ' + data.unity_status;
            }
        } catch (error) {
            console.error('Status poll error:', error);
        }
    }, 3000);
}

function showHeadsetWarning(state) {
    var existing = document.getElementById('headset-warning-modal');
    if (existing) existing.remove();

    var name = state.headsetName || 'VR headset';
    var isUnsupported = state.status === 'unsupported';

    var warning = document.createElement('div');
    warning.id = 'headset-warning-modal';
    warning.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;display:flex;align-items:center;justify-content:center;';

    warning.innerHTML = `
        <div style="background:var(--color-surface);border-radius:var(--radius-2xl);padding:2rem;max-width:480px;width:90%;text-align:center;">
            <div style="font-size:3.5rem;margin-bottom:1rem;">${isUnsupported ? '🚫' : '🖥️'}</div>
            <h3 style="margin:0 0 0.5rem;">${isUnsupported ? 'VR Not Supported' : 'Headset Not Detected'}</h3>
            <p style="color:var(--color-text-secondary);margin:0 0 1.5rem;">
                ${isUnsupported
                    ? 'Your browser does not support WebXR. Please use a WebXR-enabled browser like Meta Quest Browser, Chrome, or Edge.'
                    : 'We could not detect your VR headset. Please ensure it is connected and powered on, then try again.'
                }
            </p>
            <div style="padding:1rem;background:var(--color-surface-muted);border-radius:var(--radius-xl);margin-bottom:1.5rem;text-align:left;">
                <p style="font-weight:600;margin:0 0 0.5rem;font-size:0.85rem;">Quick tips:</p>
                <ul style="margin:0;padding-left:1.25rem;font-size:0.8rem;color:var(--color-text-secondary);display:flex;flex-direction:column;gap:0.25rem;">
                    <li>Connect your headset via USB Link cable or Air Link</li>
                    <li>Make sure the headset is powered on</li>
                    <li>Enable Oculus Link from the headset menu</li>
                    <li>Restart the Oculus app on your PC</li>
                </ul>
            </div>
            <div style="display:flex;gap:0.5rem;justify-content:center;">
                <button onclick="document.getElementById('headset-warning-modal').remove()" class="btn btn-secondary">Cancel</button>
                <button onclick="retryLaunch()" class="btn btn-primary">Try Again</button>
            </div>
        </div>
    `;

    document.body.appendChild(warning);
}

function retryLaunch() {
    var warning = document.getElementById('headset-warning-modal');
    if (warning) warning.remove();

    if (window.VRDetector) {
        VRDetector.detect().then(function (state) {
            if (state.connected) {
                var btn = document.querySelector('[onclick*="launchUnityVR"]');
                if (btn) btn.click();
            } else {
                showHeadsetWarning(state);
            }
        });
    }
}

function closeUnityModal() {
    const modal = document.getElementById('unity-launch-modal');
    if (modal) modal.remove();
    const styles = document.getElementById('unity-modal-styles');
    if (styles) styles.remove();
    if (statusPollInterval) {
        clearInterval(statusPollInterval);
        statusPollInterval = null;
    }
}

async function endUnitySession() {
    if (!currentSessionId) return;

    const elapsed = prompt('Enter session duration in seconds:', '600');
    if (elapsed === null) return;

    const duration = parseInt(elapsed);
    if (isNaN(duration) || duration <= 0) {
        alert('Please enter a valid duration.');
        return;
    }

    try {
        const moodResponse = await fetch('/api/user/current-mood', {
            headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' }
        });
        const moodData = await moodResponse.json();
        const moodAfter = moodData.mood ? moodData.mood.mood_scale : null;

        const response = await fetch('/api/vr/end-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                session_id: currentSessionId,
                session_duration: duration,
                mood_after: moodAfter,
                session_quality: null,
                notes: 'Ended from web dashboard'
            })
        });

        const data = await response.json();
        if (data.success) {
            showSessionFeedback();
        }
    } catch (error) {
        console.error('Error ending session:', error);
        alert('Error ending session.');
    }
}

function showSessionFeedback() {
    closeUnityModal();

    const feedbackModal = document.createElement('div');
    feedbackModal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:10000;display:flex;align-items:center;justify-content:center;';
    feedbackModal.innerHTML = `
        <div style="background:var(--color-surface);padding:1.5rem;border-radius:var(--radius-2xl);max-width:480px;width:90%;">
            <h3 style="margin-top:0;">Session Complete!</h3>
            <p style="color:var(--color-text-secondary);margin-bottom:1.25rem;">How was your experience?</p>
            <div style="margin-bottom:1.25rem;">
                <label style="font-weight:600;font-size:0.9rem;">Mood after session (1-10)</label>
                <input type="range" id="mood-after" min="1" max="10" value="5" style="width:100%;margin:0.5rem 0;accent-color:var(--color-primary);">
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:var(--color-text-muted);">
                    <span>1 (Worse)</span>
                    <span id="mood-value" style="font-weight:700;color:var(--color-text);">5</span>
                    <span>10 (Much Better)</span>
                </div>
            </div>
            <div style="margin-bottom:1.25rem;">
                <label style="font-weight:600;font-size:0.9rem;">Session Quality</label>
                <div id="star-rating" style="font-size:1.75rem;margin:0.5rem 0;cursor:pointer;color:var(--color-warning);">★★★★★</div>
            </div>
            <div style="margin-bottom:1.25rem;">
                <label style="font-weight:600;font-size:0.9rem;">Notes (optional)</label>
                <textarea id="session-notes" rows="3" style="width:100%;padding:0.625rem;border:1px solid var(--color-border);border-radius:var(--radius-lg);background:var(--color-surface);color:var(--color-text);margin-top:0.375rem;" placeholder="How did you feel during this session?"></textarea>
            </div>
            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                <button onclick="this.closest('div[style*=\\'position:fixed\\']').remove()" class="btn btn-secondary btn-sm">Skip</button>
                <button id="submit-feedback-btn" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </div>
    `;

    document.body.appendChild(feedbackModal);

    const moodSlider = feedbackModal.querySelector('#mood-after');
    const moodValue = feedbackModal.querySelector('#mood-value');
    moodSlider.addEventListener('input', () => { moodValue.textContent = moodSlider.value; });

    let currentRating = 5;
    const starRating = feedbackModal.querySelector('#star-rating');
    starRating.addEventListener('click', (e) => {
        const rect = starRating.getBoundingClientRect();
        const x = e.clientX - rect.left;
        currentRating = Math.ceil((x / rect.width) * 5);
        starRating.textContent = '★'.repeat(currentRating) + '☆'.repeat(5 - currentRating);
    });

    feedbackModal.querySelector('#submit-feedback-btn').addEventListener('click', async () => {
        const moodAfter = parseInt(moodSlider.value);
        try {
            await fetch('/api/vr-sessions/end', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: currentSessionId,
                    session_duration: 0,
                    mood_after: moodAfter,
                    session_quality: currentRating,
                    notes: feedbackModal.querySelector('#session-notes').value
                })
            });
        } catch (e) {
            console.error('Error saving feedback:', e);
        }
        feedbackModal.remove();
        alert('Thank you for your feedback!');
    });
}

document.addEventListener('DOMContentLoaded', function () {
  updateVrAssetsStatus();
  if (window.VRDetector) {
    VRDetector.addEventListener(updateVrAssetsStatus);
  }
});

function updateVrAssetsStatus() {
  var badge = document.getElementById('vr-assets-status-badge');
  if (!badge || !window.VRDetector) return;

  var state = VRDetector.getState();

  if (state.status === 'connected') {
    badge.className = 'badge badge--success';
    badge.textContent = state.headsetName ? state.headsetName + ' Connected' : 'VR Headset Connected';
  } else if (state.status === 'not-connected') {
    badge.className = 'badge badge--warning';
    badge.textContent = 'No Headset Detected — Connect via Link or Air Link';
  } else if (state.status === 'unsupported') {
    badge.className = 'badge badge--danger';
    badge.textContent = 'VR Not Supported — Use a WebXR-enabled browser';
  } else {
    badge.className = 'badge badge--neutral';
    badge.textContent = 'Checking VR connection...';
  }
}
</script>
@endsection
