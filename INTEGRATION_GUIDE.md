# Neuro Haven — Laravel + Unity VR Integration Guide

## Table of Contents
1. [Architecture Overview](#1-architecture-overview)
2. [System Requirements](#2-system-requirements)
3. [Project Structure](#3-project-structure)
4. [Setup Instructions](#4-setup-instructions)
5. [How It Works](#5-how-it-works)
6. [API Reference](#6-api-reference)
7. [Unity Scene Setup](#7-unity-scene-setup)
8. [Meta Quest Connection](#8-meta-quest-connection)
9. [Local Testing Workflow](#9-local-testing-workflow)
10. [Error Handling](#10-error-handling)

---

## 1. Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                   User's Browser (Laravel UI)                    │
│  ┌───────────────────────────────────────────────────────────┐   │
│  │  VR Assets Page                                           │   │
│  │  ┌──────────────────┐   ┌──────────────────┐              │   │
│  │  │ Forest Walk      │   │ Ocean Meditation │  ...         │   │
│  │  │ [Launch Exp.]    │   │ [Launch Exp.]    │              │   │
│  │  └────────┬─────────┘   └────────┬─────────┘              │   │
│  └───────────┼──────────────────────┼──────────────────────────┘   │
│              │ POST /api/vr/launch-unity                          │
└──────────────┼────────────────────────────────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────────────────────────────┐
│                     Laravel Backend (:8000)                       │
│  ┌─────────────────────┐    ┌────────────────────────────────┐   │
│  │ UnityBridgeController│    │  UnityLauncherService          │   │
│  │  ┌───────────────┐   │    │  - Validates config            │   │
│  │  │ POST launch() │   │    │  - Spawns Unity via PS script  │   │
│  │  │ GET config()  │───┼───→│  - Monitors process            │   │
│  │  │ GET status()  │   │    └────────────────────────────────┘   │
│  │  └───────┬───────┘   │                                          │
│  └──────────┼────────────┘                                          │
│             │                                                       │
│             │ 1. Creates VRSession in DB                            │
│             │ 2. POST /start-session to Node.js backend             │
│             │ 3. Spawns Unity executable via PowerShell             │
└─────────────┼──────────────────────────────────────────────────────┘
              │
              │ HTTP POST (Guzzle)
              ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    Node.js Backend (:3000)                           │
│  ┌──────────────────────────────────────┐                          │
│  │  Express REST API                    │                          │
│  │  POST /start-session                 │                          │
│  │  GET  /current-session  ◄────────────┼──── Polled by Unity     │
│  │  GET  /end-session                   │                          │
│  └──────────────────────────────────────┘                          │
│                                                                     │
│  Store: session-store.json (persists session state)                 │
└─────────────────────────────────────────────────────────────────────┘
              ▲
              │ HTTP polling every 3 seconds
              │
┌─────────────────────────────────────────────────────────────────────┐
│                Unity VR Application (Windows)                       │
│  ┌──────────────────────────────────────┐                          │
│  │  NeuroHavenSessionManager.cs         │                          │
│  │  - Polls /current-session            │                          │
│  │  - Loads scene based on scene key    │                          │
│  │  - Handles fade transitions          │                          │
│  │  - Plays environment audio           │                          │
│  └──────────────────────────────────────┘                          │
│  ┌──────────────────────────────────────┐                          │
│  │  VRBridge.cs                         │                          │
│  │  - Parses command-line args          │                          │
│  │  - Direct session start support      │                          │
│  │  - Reports session completion        │                          │
│  └──────────────────────────────────────┘                          │
│                                                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │  OpenXR / Meta XR Plugin                                     │   │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐    │   │
│  │  │ Forest   │  │  Beach   │  │ Mountain │  │ Breathing│    │   │
│  │  │ Scene    │  │  Scene   │  │  Scene   │  │  Scene   │    │   │
│  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘    │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                     │
│         ╔══════════════════════════╗                                │
│         ║  Meta Quest Headset      ║                                │
│         ║  (USB Link / Air Link)   ║                                │
│         ╚══════════════════════════╝                                │
└─────────────────────────────────────────────────────────────────────┘
```

**Data Flow:**

```
User Click  →  Laravel  →  Node.js Backend  →  Unity polls  →  Scene Loads
   │              │              │                   │               │
   │    1. Create DB session    │                   │               │
   │    2. POST /start-session ─┘                   │               │
   │    3. Launch Unity.exe      │                   │               │
   │                            │   GET /current-session ────────────┤
   │                            │                   │               │
   │                            │               ┌───┘               │
   │                            │            ┌── Scene loaded in VR │
   │                            │            │                      │
   │                            │            ▼                      │
   │                         Session runs for duration...           │
   │                            │            │                      │
   │                            │     GET /end-session               │
   │                            │            │                      │
   │                            │            ▼                      │
   │                         Session ──→ Completed                  │
```

---

## 2. System Requirements

### Software
- **Windows 10/11** (PC for development and Unity runtime)
- **Laravel 11+** with PHP 8.3+
- **Node.js 18+** (for the backend bridge server)
- **Unity 2022.3+** (LTS recommended) with:
  - OpenXR Plugin (com.unity.xr.openxr)
  - XR Interaction Toolkit (com.unity.xr.interaction.toolkit)
- **Meta Quest** headset (Quest 2, Quest 3, or Quest Pro)
- **Meta Quest Link** app on PC (for wired or Air Link connection)
- **Git** for version control

### Hardware
- Windows PC with dedicated GPU (NVIDIA GTX 1060 / AMD RX 580 or better)
- 16GB+ RAM
- Meta Quest headset with USB-C cable or Wi-Fi (Air Link)

---

## 3. Project Structure

```
neuro-haven/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── UnityBridgeController.php   # Unity launch API
│   │           └── VRSessionsController.php    # Session tracking
│   ├── Models/
│   │   ├── VRAsset.php                         # VR asset model
│   │   └── VRSession.php                       # VR session model
│   └── Services/
│       ├── NotificationService.php
│       └── UnityLauncherService.php            # Unity process management
├── backend/
│   ├── server.js                               # Node.js backend API
│   ├── package.json
│   └── session-store.json                      # Session state persistence
├── config/
│   └── services.php                            # Unity & backend config
├── resources/
│   └── views/
│       └── vr-assets.blade.php                 # VR assets page with Launch button
├── routes/
│   └── web.php                                 # API routes
├── scripts/
│   └── launch-unity.ps1                        # PowerShell launcher
├── unity/
│   ├── NeuroHavenSessionManager.cs             # Session polling & scene switching
│   └── VRBridge.cs                             # Direct session start from CLI
├── .env                                        # Environment configuration
└── INTEGRATION_GUIDE.md                        # This file
```

---

## 4. Setup Instructions

### Step 1: Configure Environment Variables

Edit `.env` and set your Unity executable path:

```env
BACKEND_URL=http://localhost:3000
UNITY_EXECUTABLE_PATH=C:\NeuroHaven\Builds\NeuroHavenVR.exe
UNITY_SCENE_FOREST=ForestScene
UNITY_SCENE_BEACH=BeachScene
UNITY_SCENE_MOUNTAIN=MountainScene
UNITY_SCENE_BREATHING=BreathingScene
```

### Step 2: Start the Node.js Backend

```powershell
cd backend
npm install
npm start
```

Expected output:
```
[backend] Neuro Haven API listening on http://0.0.0.0:3000
[backend] WebSocket server ready for connections
```

### Step 3: Start the Laravel Backend

In a separate terminal:

```powershell
php artisan serve --port=8000
```

### Step 4: Configure Unity Project

In your Unity project (the one you build to `C:\NeuroHaven\Builds\`):

1. **Import the scripts:**
   - Copy `unity/NeuroHavenSessionManager.cs` into your Unity project's `Scripts/` folder
   - Copy `unity/VRBridge.cs` into the same folder

2. **Set up OpenXR:**
   - Go to `Edit → Project Settings → XR Plug-in Management`
   - Enable **OpenXR** for Windows platform
   - In the OpenXR settings, add **Meta Quest** interaction profile

3. **Configure scenes:**
   - Create scenes: `ForestScene`, `BeachScene`, `MountainScene`, `BreathingScene`
   - Add them to `File → Build Settings → Scenes in Build`
   - Make sure the scene names match your `.env` configuration

4. **Add the managers to your initial scene:**
   - Create an empty GameObject called "SessionManager"
   - Add `NeuroHavenSessionManager.cs` component
   - Add `VRBridge.cs` component
   - Set `Backend URL` to `http://<YOUR_LOCAL_IP>:3000` (use your PC's local IP so the Quest can reach it)

5. **Build the Unity project:**
   - `File → Build Settings`
   - Target: **Windows, Mac, Linux** → **Windows**
   - Architecture: **x86_64**
   - Build to: `C:\NeuroHaven\Builds\NeuroHavenVR.exe`

### Step 5: Connect Meta Quest

**Option A: USB Link Cable (recommended for development)**
1. Install **Meta Quest Link** app on your PC
2. Connect Quest via USB-C cable
3. In Quest, accept "Allow data access" and "Enable Link"
4. Launch the Unity build from Laravel — it will render to the connected Quest

**Option B: Air Link (wireless)**
1. Ensure both PC and Quest are on the same 5GHz Wi-Fi network
2. In Quest: `Settings → System → Quest Link → Pair`
3. On PC: Meta Quest Link app → Launch Air Link
4. Connect from the Quest menu

---

## 5. How It Works

### Button Click → Unity Launch

1. **User visits** `/vr-assets` page in the Laravel web app
2. **User clicks** "Launch Experience" on a VR asset (e.g., Peaceful Forest Walk)
3. **Browser sends** `POST /api/vr/launch-unity` with `{ vr_asset_id: 1 }`
4. **Laravel's `UnityBridgeController@launch`**:
   - Looks up the asset in the database
   - Maps the asset category to a scene key (`forest`, `beach`, `mountain`, `breathing`)
   - Creates a `VRSession` record in SQLite
   - Sends `POST /start-session` to the Node.js backend with `{ scene: "forest", duration: 600 }`
   - Calls `UnityLauncherService@launchViaPowerShell` which runs `scripts/launch-unity.ps1`
   - The PowerShell script starts `NeuroHavenVR.exe` with command-line arguments:
     ```
     NeuroHavenVR.exe -sceneKey forest -durationSeconds 600 -backendUrl http://localhost:3000
     ```
5. **Unity starts**, `VRBridge.Awake()` parses the command-line arguments
6. **Unity polls** `GET /current-session` from the Node.js backend every 3 seconds
7. **Unity detects** `status: "start"` or `status: "running"` with the scene key
8. **Unity loads** the mapped scene (e.g., `ForestScene`) with a fade transition
9. **Unity plays** environment audio for the scene
10. **Session runs** for the configured duration, then Unity auto-ends

### Session Status Polling

While Unity runs, the browser polls `POST /api/vr/check-status` every 3 seconds:
- Uses the `session_id` to query the VRSession model
- Also checks the Node.js backend for real-time session state
- Updates the UI with status: "Waiting for Unity...", "Preparing VR...", "VR active", "Completed"

---

## 6. API Reference

### Laravel Endpoints (protected by auth)

#### `POST /api/vr/launch-unity`
Trigger a Unity VR session from the web UI.

**Request:**
```json
{
    "vr_asset_id": 1,
    "mood_before": 7
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Unity VR experience launching...",
    "session": {
        "id": 42,
        "user_id": 1,
        "vr_asset_id": 1,
        "vr_asset_title": "Peaceful Forest Walk",
        "started_at": "2026-05-23T16:00:00Z"
    },
    "scene_key": "forest",
    "duration_seconds": 600
}
```

**Response (500 - Unity not found):**
```json
{
    "success": false,
    "error": "Unity executable not found at: C:\\Unity\\notfound.exe",
    "session": { "id": 42 }
}
```

#### `POST /api/vr/check-status`
Check real-time session status.

**Request:**
```json
{
    "session_id": 42
}
```

**Response:**
```json
{
    "session": { "id": 42, "completed_at": null },
    "unity_status": "running",
    "is_completed": false
}
```

`unity_status` can be: `idle`, `start`, `running`, `ended`, `backend-unreachable`, `unknown`

#### `POST /api/vr/end-session`
Manually end a VR session from the web UI.

**Request:**
```json
{
    "session_id": 42,
    "session_duration": 540,
    "mood_after": 8,
    "session_quality": 4,
    "notes": "Felt very relaxed after the session"
}
```

**Response:**
```json
{
    "success": true,
    "session": { "id": 42, "completed_at": "2026-05-23T16:10:00Z" }
}
```

#### `GET /api/vr/scenes`
List all available VR scenes with their Unity scene keys.

#### `GET /api/vr/config`
Get Unity bridge configuration status.

**Response:**
```json
{
    "backend_url": "http://localhost:3000",
    "unity_configured": true,
    "unity_path": "C:\\NeuroHaven\\Builds\\NeuroHavenVR.exe"
}
```

### Node.js Backend Endpoints

#### `POST /start-session`
Set the current session for Unity to pick up.

**Request:**
```json
{
    "scene": "forest",
    "duration": 600,
    "session_id": 42,
    "user_id": 1
}
```

#### `GET /current-session`
Get the current session state (polled by Unity).

**Response:**
```json
{
    "scene": "forest",
    "status": "running",
    "duration": 600,
    "remainingSeconds": 480,
    "displayName": "Peaceful Forest Walk",
    "sessionId": 42,
    "userId": 1
}
```

#### `GET /end-session`
End the current session.

---

## 7. Unity Scene Setup

### Required Scenes in Build Settings

Your Unity project must have at least these scenes in `File → Build Settings`:

| Scene Name | Scene Key | Build Index | Description |
|-----------|-----------|-------------|-------------|
| `InitialScene` | - | 0 | Loading/init scene with the SessionManager |
| `ForestScene` | `forest` | 1 | Peaceful forest environment |
| `BeachScene` | `beach` | 2 | Ocean meditation environment |
| `MountainScene` | `mountain` | 3 | Mountain view therapy |
| `BreathingScene` | `breathing` | 4 | Guided breathing exercise |

### Scene Mapping Configuration

The `NeuroHavenSessionManager` component maps scene keys to build scene names:

| Inspector Field | Default Value | Scene Key |
|----------------|--------------|-----------|
| `forestSceneName` | `ForestScene` | `forest` |
| `beachSceneName` | `BeachScene` | `beach` |
| `mountainSceneName` | `MountainScene` | `mountain` |
| `breathingSceneName` | `BreathingScene` | `breathing` |

### XR Setup Checklist

- [ ] OpenXR Plugin installed via Package Manager
- [ ] XR Plug-in Management enabled for Windows
- [ ] Meta Quest interaction profile added in OpenXR
- [ ] All scenes have XR camera rigs (Main Camera replaced with XR Origin)
- [ ] `Initialize XR on Startup` enabled in XR Management
- [ ] Build target set to Windows and architecture x86_64

---

## 8. Meta Quest Connection

### Wired (USB Link) — Recommended for testing

1. Install **Meta Quest Link** from https://www.meta.com/quest/setup/
2. Connect Quest to PC via USB-C cable
3. Put on Quest — you'll see a prompt: "Enable Link" — confirm
4. Launch the Unity build from Laravel
5. The Unity window on your PC monitor will show the VR view
6. Put on the Quest to see the VR environment

**Troubleshooting:**
- If Link doesn't connect: restart the Meta Quest Link app
- If Unity doesn't render to Quest: ensure OpenXR is properly configured
- Use `oculus-diagnostics.exe` (from Meta Quest Link installation) to test the connection

### Wireless (Air Link) — For final testing

1. Both PC and Quest on same 5GHz Wi-Fi network
2. PC: Meta Quest Link app → `Settings → Beta` → Enable Air Link
3. Quest: `Quick Settings → Quest Link` → Select your PC → Launch
4. Same as wired once connected

### Unity Build and Run for Quest

1. Set build target to **Windows**
2. Connect Quest via USB Link
3. In Unity, `File → Build and Run`
4. The build deploys, starts automatically, and appears in the Quest
5. For subsequent launches, use the Laravel Launch Experience button

---

## 9. Local Testing Workflow

### Quick Start (all services)

```powershell
# Terminal 1: Node.js backend
cd neuro-haven
cd backend
npm start

# Terminal 2: Laravel backend
cd neuro-haven
php artisan serve --port=8000

# Terminal 3 (optional): Vite dev server
cd neuro-haven
npm run dev
```

### Testing the Full Flow

1. **Start both backends** (Laravel :8000 and Node.js :3000)
2. **Log in** to Laravel at http://localhost:8000/login
3. **Navigate** to VR Assets (sidebar → VR Library)
4. **Click** "Launch Experience" on any asset
5. **Observe** the launch modal in the browser
6. **Check** the PowerShell log in `storage/logs/unity-launch-*.log`
7. **Verify** Unity starts (or see the error if the path is wrong)
8. **Polling** begins — the browser updates the modal every 3 seconds

### Testing Without Unity

If you don't have Unity built yet, you can test the API flow:

```powershell
# Test the API directly with curl
curl -X POST http://localhost:8000/api/vr/launch-unity ^
  -H "Content-Type: application/json" ^
  -H "X-CSRF-TOKEN: <csrf>" ^
  -d "{\"vr_asset_id\": 1}"

# Check the backend session
curl http://localhost:3000/current-session
```

### Debugging Tips

| Issue | Where to Check | What to Look For |
|-------|---------------|------------------|
| Button click does nothing | Browser console (F12) | Network error, CSRF mismatch |
| Unity doesn't start | `storage/logs/unity-launch-*.log` | Path not found, permissions |
| Backend connection refused | Laravel log, terminal | Node.js not running, port conflict |
| Quest not showing VR | Meta Quest Link app | Cable connection, Air Link status |
| Scene not loading | Unity Console window | Scene name mismatch, build index |

---

## 10. Error Handling

### Laravel-side Errors

| Scenario | HTTP Status | Response | User Sees |
|----------|-------------|----------|-----------|
| Asset not found | 404 | - | "Page not found" |
| Unity path not configured | 500 | `{"success":false,"error":"Unity executable path not configured..."}` | Modal shows error |
| Unity exe not found | 500 | `{"success":false,"error":"Unity executable not found at: ..."}` | Modal shows error |
| Backend server down | 500 | Session still created, but Unity status unknown | "Waiting for Unity..." |
| Invalid asset ID | 422 | Validation error | Form error |

### Unity-side Errors

| Scenario | Log Message | Behavior |
|----------|-------------|----------|
| Backend unreachable | `Failed to fetch current session` | Continues polling, retries next interval |
| Unknown scene key | `Unknown scene mapping for key: ...` | Logs warning, stays in current scene |
| Session expired | `Session duration reached` | Auto-ends, notifies backend |
| Build scene missing | `Scene not found in build settings` | SceneManager.LoadScene logs error |

### Node.js Backend Errors

| Scenario | Status | Response |
|----------|--------|----------|
| Invalid scene | 400 | `{"error":"scene must be one of: forest, beach, ..."}` |
| Missing duration | 400 | `{"error":"duration is required and must be a number."}` |
| No active session | 200 | `{"scene":null,"status":"idle","duration":0}` |
| Internal error | 500 | `{"error":"Internal server error."}` |

### Frontend Error Handling

The `vr-assets.blade.php` JavaScript includes multiple fallbacks:

```javascript
// Button is re-enabled on failure
btn.disabled = false;
btn.innerHTML = originalText;

// Status polling handles backend being down
case 'backend-unreachable':
    statusText.textContent = 'Connecting to backend service...';
    break;

// Session can be ended manually
if (endBtn) endBtn.style.display = 'inline-flex';
```

### Recommended Error Logging

Add this to `UnityLauncherService.php` to capture Unity crashes:

```php
// Already included: output is redirected to storage/logs/unity-launch-*.log
$logFile = storage_path("logs/unity-launch-" . now()->format('Ymd-His') . ".log");
$wrappedCmd = "cmd /c START \"NeuroHavenUnity\" /B {$cmd} > \"{$logFile}\" 2>&1";
```

---

## Quick Reference: File Purposes

| File | Purpose |
|------|---------|
| `UnityBridgeController.php` | Handles web-initiated Unity launch requests |
| `UnityLauncherService.php` | Manages Unity executable process on Windows |
| `services.php` | Configuration for backend URL + Unity path |
| `web.php` | Route definitions for Unity bridge API |
| `vr-assets.blade.php` | Frontend page with Launch Experience button |
| `launch-unity.ps1` | PowerShell script to start Unity with args |
| `NeuroHavenSessionManager.cs` | Unity-side session polling and scene management |
| `VRBridge.cs` | Unity-side command-line argument parsing and direct launch |
| `server.js` | Node.js REST API for session state management |
