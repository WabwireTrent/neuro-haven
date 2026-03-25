@extends('layouts.app')

@section('title', 'VR Therapeutic Assets')
@section('page', 'vr-assets')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="dashboard-greeting">
                    <h1>VR Therapeutic Assets</h1>
                    <p class="dashboard-streak">Immersive therapy experiences</p>
                </div>
            </header>

            <section class="dashboard-main">
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Available VR Experiences</h2>
                        <span class="dashboard-widget__eyebrow">Therapeutic Assets</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
                        @foreach($assets as $asset)
                        <article class="surface p-4" style="border-radius: var(--radius-lg); overflow: hidden;">
                            <div style="height: 150px; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); border-radius: var(--radius-md); margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: white;">
                                <span style="font-size: 2rem;">
                                    @switch($asset['id'])
                                        @case(1) 🌿 @break
                                        @case(2) 🌊 @break
                                        @case(3) 🏔️ @break
                                        @case(4) 🫁 @break
                                        @case(5) ⭐ @break
                                        @case(6) 🏮 @break
                                    @endswitch
                                </span>
                            </div>
                            <h3 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: 600;">{{ $asset['title'] }}</h3>
                            <p class="text-muted" style="margin: 0 0 1rem; font-size: 0.875rem;">{{ $asset['description'] }}</p>
                            <div class="cluster" style="margin-bottom: 1rem;">
                                <span class="badge">{{ $asset['category'] }}</span>
                                <span class="badge">{{ $asset['duration'] }}</span>
                            </div>
                            <button class="btn btn-primary" style="width: 100%;" onclick="launchVR({{ $asset['id'] }})">
                                Launch Experience
                            </button>
                        </article>
                        @endforeach
                    </div>
                </section>

                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>VR Setup Instructions</h2>
                    </div>
                    <div class="stack" style="gap: 1rem;">
                        <div class="surface p-4">
                            <h4 style="margin: 0 0 0.5rem;">1. Connect Your VR Headset</h4>
                            <p class="text-muted" style="margin: 0;">Ensure your VR headset is properly connected and powered on.</p>
                        </div>
                        <div class="surface p-4">
                            <h4 style="margin: 0 0 0.5rem;">2. Enable WebXR</h4>
                            <p class="text-muted" style="margin: 0;">Make sure your browser supports WebXR and VR is enabled in settings.</p>
                        </div>
                        <div class="surface p-4">
                            <h4 style="margin: 0 0 0.5rem;">3. Start Your Session</h4>
                            <p class="text-muted" style="margin: 0;">Click "Launch Experience" on any asset to begin your VR therapy session.</p>
                        </div>
                    </div>
                </section>
            </section>
        </div>
    </div>
</div>

<script>
async function launchVR(assetId) {
    if (!navigator.xr) {
        alert('WebXR is not supported on this device/browser.');
        return;
    }

    try {
        const supported = await navigator.xr.isSessionSupported('immersive-vr');
        if (!supported) {
            alert('Immersive VR is not supported. Please check your VR headset connection.');
            return;
        }

        // Get current mood before session
        const moodBefore = await getCurrentMood();

        // Start VR session tracking
        const sessionData = await startVRSession(assetId, moodBefore);

        // Start WebXR session
        const session = await navigator.xr.requestSession('immersive-vr', {
            requiredFeatures: ['local-floor', 'bounded-floor']
        });

        // Show VR experience
        showVRExperience(assetId, session, sessionData);

    } catch (error) {
        console.error('Error starting VR session:', error);
        alert('Error starting VR session. Please try again.');
    }
}

async function getCurrentMood() {
    // Get today's mood from the server
    try {
        const response = await fetch('/api/user/current-mood', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        return data.mood ? data.mood.mood_scale : null;
    } catch (error) {
        console.error('Error getting current mood:', error);
        return null;
    }
}

async function startVRSession(assetId, moodBefore) {
    const assets = @json($assets);
    const asset = assets.find(a => a.id === assetId);

    try {
        const response = await fetch('/api/vr-sessions/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                vr_asset_id: assetId,
                vr_asset_title: asset.title,
                mood_before: moodBefore,
                device_type: navigator.xr ? 'vr-headset' : 'browser'
            })
        });
        return await response.json();
    } catch (error) {
        console.error('Error starting session tracking:', error);
        return null;
    }
}

async function endVRSession(sessionId, duration, moodAfter, quality, notes) {
    try {
        await fetch('/api/vr-sessions/end', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                session_id: sessionId,
                session_duration: duration,
                mood_after: moodAfter,
                session_quality: quality,
                notes: notes
            })
        });
    } catch (error) {
        console.error('Error ending session tracking:', error);
    }
}

function showVRExperience(assetId, session, sessionData) {
    const startTime = Date.now();
    let sessionId = sessionData?.id;
    const canvas = document.createElement('canvas');
    canvas.width = 1920;
    canvas.height = 1080;
    const ctx = canvas.getContext('2d');

    // Simple animated scene based on asset
    let frame = 0;
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Different scenes based on asset
        switch(assetId) {
            case 1: // Forest
                ctx.fillStyle = '#228B22';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#32CD32';
                for(let i = 0; i < 50; i++) {
                    ctx.beginPath();
                    ctx.arc(Math.sin(frame * 0.01 + i) * 200 + canvas.width/2,
                           Math.cos(frame * 0.01 + i) * 200 + canvas.height/2,
                           10, 0, Math.PI * 2);
                    ctx.fill();
                }
                break;
            case 2: // Ocean
                ctx.fillStyle = '#1e90ff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#ffffff';
                for(let i = 0; i < 20; i++) {
                    ctx.beginPath();
                    ctx.arc(Math.sin(frame * 0.02 + i) * 300 + canvas.width/2,
                           canvas.height - 100 + Math.sin(frame * 0.05 + i) * 20,
                           15, 0, Math.PI * 2);
                    ctx.fill();
                }
                break;
            case 3: // Mountain
                ctx.fillStyle = '#87CEEB';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#8B4513';
                ctx.beginPath();
                ctx.moveTo(0, canvas.height);
                ctx.lineTo(canvas.width/2, canvas.height/3);
                ctx.lineTo(canvas.width, canvas.height);
                ctx.fill();
                break;
            case 4: // Breathing
                ctx.fillStyle = '#000000';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#ffffff';
                ctx.font = '48px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('Breathe In...', canvas.width/2, canvas.height/2 - 50);
                ctx.fillText(Math.floor(Math.sin(frame * 0.05) * 2 + 3) + ' seconds', canvas.width/2, canvas.height/2 + 50);
                break;
            case 5: // Stars
                ctx.fillStyle = '#000011';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#ffffff';
                for(let i = 0; i < 100; i++) {
                    const x = (i * 37) % canvas.width;
                    const y = (i * 23) % canvas.height;
                    const brightness = Math.sin(frame * 0.02 + i) * 0.5 + 0.5;
                    ctx.globalAlpha = brightness;
                    ctx.beginPath();
                    ctx.arc(x, y, 2, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1;
                break;
            case 6: // Zen Garden
                ctx.fillStyle = '#F5F5DC';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#228B22';
                // Draw rocks
                for(let i = 0; i < 15; i++) {
                    ctx.beginPath();
                    ctx.ellipse(100 + i * 120, 200 + Math.sin(i) * 100, 30, 20, Math.PI / 4, 0, Math.PI * 2);
                    ctx.fill();
                }
                // Draw sand patterns
                ctx.strokeStyle = '#D2B48C';
                ctx.lineWidth = 2;
                for(let i = 0; i < 10; i++) {
                    ctx.beginPath();
                    ctx.arc(canvas.width/2, canvas.height/2, 50 + i * 20, 0, Math.PI * 2);
                    ctx.stroke();
                }
                break;
        }

        frame++;
        requestAnimationFrame(animate);
    }

    animate();

    // Show modal with VR content and session controls
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: black; z-index: 9999; display: flex; align-items: center; justify-content: center;
        flex-direction: column;
    `;
    modal.innerHTML = `
        <div style="text-align: center; color: white; max-width: 600px; padding: 20px;">
            <h2>VR Experience Active</h2>
            <p>Put on your VR headset to view the therapeutic scene.</p>
            <div id="session-timer" style="font-size: 24px; margin: 20px 0;">00:00</div>
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <button id="end-session-btn" onclick="endSession()" style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer;">End Session</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Session timer
    let sessionStart = Date.now();
    const timerElement = modal.querySelector('#session-timer');
    const timerInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - sessionStart) / 1000);
        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
        const seconds = (elapsed % 60).toString().padStart(2, '0');
        timerElement.textContent = `${minutes}:${seconds}`;
    }, 1000);

    // End session function
    window.endSession = async () => {
        clearInterval(timerInterval);
        const duration = Math.floor((Date.now() - startTime) / 1000);

        // Show feedback modal
        showSessionFeedback(sessionId, duration, () => {
            modal.remove();
            session.end();
        });
    };

    // Auto-end session after max duration (30 minutes)
    setTimeout(() => {
        if (modal.parentElement) {
            endSession();
        }
    }, 30 * 60 * 1000);
}

function showSessionFeedback(sessionId, duration, callback) {
    const feedbackModal = document.createElement('div');
    feedbackModal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8); z-index: 10000; display: flex; align-items: center; justify-content: center;
    `;
    feedbackModal.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
            <h3 style="margin-top: 0;">Session Complete!</h3>
            <p>Duration: ${Math.floor(duration / 60)}:${(duration % 60).toString().padStart(2, '0')}</p>

            <div style="margin: 20px 0;">
                <label>How was your mood after the session? (1-10)</label><br>
                <input type="range" id="mood-after" min="1" max="10" value="5" style="width: 100%; margin: 10px 0;">
                <div style="display: flex; justify-content: space-between; font-size: 12px;">
                    <span>1 (Worse)</span>
                    <span id="mood-value">5</span>
                    <span>10 (Much Better)</span>
                </div>
            </div>

            <div style="margin: 20px 0;">
                <label>Session Quality (1-5 stars)</label><br>
                <div id="star-rating" style="font-size: 24px; margin: 10px 0; cursor: pointer;">
                    ★☆☆☆☆
                </div>
            </div>

            <div style="margin: 20px 0;">
                <label>Notes (optional)</label><br>
                <textarea id="session-notes" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="How did you feel during this session?"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="this.closest('div').parentElement.remove()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer;">Skip</button>
                <button id="submit-feedback" style="padding: 10px 20px; background: var(--color-primary); color: white; border: none; border-radius: 5px; cursor: pointer;">Submit Feedback</button>
            </div>
        </div>
    `;

    document.body.appendChild(feedbackModal);

    // Mood slider
    const moodSlider = feedbackModal.querySelector('#mood-after');
    const moodValue = feedbackModal.querySelector('#mood-value');
    moodSlider.addEventListener('input', () => {
        moodValue.textContent = moodSlider.value;
    });

    // Star rating
    let currentRating = 3;
    const starRating = feedbackModal.querySelector('#star-rating');
    starRating.addEventListener('click', (e) => {
        const rect = starRating.getBoundingClientRect();
        const x = e.clientX - rect.left;
        currentRating = Math.ceil((x / rect.width) * 5);
        updateStars();
    });

    function updateStars() {
        starRating.textContent = '★'.repeat(currentRating) + '☆'.repeat(5 - currentRating);
    }

    // Submit feedback
    feedbackModal.querySelector('#submit-feedback').addEventListener('click', async () => {
        const moodAfter = parseInt(moodSlider.value);
        const quality = currentRating;
        const notes = feedbackModal.querySelector('#session-notes').value;

        await endVRSession(sessionId, duration, moodAfter, quality, notes);
        feedbackModal.remove();
        callback();
    });
}
</script>

<style>
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: var(--color-surface-muted);
        color: var(--color-text-muted);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
    }
    .mb-4 { margin-bottom: var(--space-4); }
    .p-6 { padding: var(--space-6); }
    .p-4 { padding: var(--space-4); }
    .grid {
        display: grid;
    }
    .stack {
        display: flex;
        flex-direction: column;
    }
    .cluster {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
</style>
@endsection