<#
.SYNOPSIS
    Launch Neuro Haven Unity VR application on Windows.
.DESCRIPTION
    Starts the Unity VR executable with command-line arguments for scene,
    duration, and backend URL. Used by the Laravel web system to trigger
    the VR experience on a connected Meta Quest headset.
.PARAMETER UnityPath
    Full path to the Unity executable (e.g., C:\NeuroHaven\NeuroHavenVR.exe).
.PARAMETER SceneKey
    Scene key: forest, beach, mountain, or breathing.
.PARAMETER DurationSeconds
    Session duration in seconds.
.PARAMETER BackendUrl
    URL of the Node.js backend API server.
.PARAMETER LaravelUrl
    URL of the Laravel backend API server.
.PARAMETER LogDir
    Directory to write log files (default: current directory).
.EXAMPLE
    .\launch-unity.ps1 -UnityPath "C:\NeuroHaven\NeuroHavenVR.exe" -SceneKey forest -DurationSeconds 600 -BackendUrl "http://localhost:3000"
#>

param(
    [Parameter(Mandatory = $true)]
    [string]$UnityPath,

    [Parameter(Mandatory = $true)]
    [ValidateSet('forest', 'beach', 'mountain', 'breathing')]
    [string]$SceneKey,

    [Parameter(Mandatory = $true)]
    [int]$DurationSeconds,

    [Parameter(Mandatory = $false)]
    [string]$BackendUrl = "http://localhost:3000",

    [Parameter(Mandatory = $false)]
    [string]$LaravelUrl = "http://localhost:8000",

    [Parameter(Mandatory = $false)]
    [string]$LogDir = (Get-Location).Path
)

$ErrorActionPreference = "Stop"
$logFile = Join-Path -Path $LogDir -ChildPath "unity-launch-$(Get-Date -Format 'yyyyMMdd-HHmmss').log"

function Write-Log {
    param([string]$Message)
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    "$timestamp [NeuroHaven] $Message" | Out-File -FilePath $logFile -Append -Encoding UTF8
    Write-Host "$timestamp [NeuroHaven] $Message"
}

Write-Log "============================================"
Write-Log "Neuro Haven Unity Launcher"
Write-Log "============================================"

# Validate Unity executable exists
if (-not (Test-Path -LiteralPath $UnityPath)) {
    Write-Log "ERROR: Unity executable not found at: $UnityPath"
    exit 1
}

Write-Log "Unity Path: $UnityPath"
Write-Log "Scene Key: $SceneKey"
Write-Log "Duration: ${DurationSeconds}s"
Write-Log "Backend URL: $BackendUrl"
Write-Log "Laravel URL: $LaravelUrl"
Write-Log "Log File: $logFile"

# Check if Unity is already running
$unityProcessName = [System.IO.Path]::GetFileNameWithoutExtension($UnityPath)
$existingProcess = Get-Process -Name $unityProcessName -ErrorAction SilentlyContinue

if ($existingProcess) {
    Write-Log "WARNING: Unity ($unityProcessName) is already running (PID: $($existingProcess.Id))."
    Write-Log "Sending launch signal to existing process via backend API."

    # The existing Unity instance will pick up the new session from polling
    Write-Log "Session set via backend. Existing Unity instance should pick it up."
    exit 0
}

# Build command-line arguments
$arguments = @(
    "-sceneKey", $SceneKey,
    "-durationSeconds", $DurationSeconds.ToString(),
    "-backendUrl", $BackendUrl,
    "-laravelUrl", $LaravelUrl
)

Write-Log "Starting Unity with arguments: $($arguments -join ' ')"

try {
    $process = Start-Process -FilePath $UnityPath -ArgumentList $arguments -NoNewWindow -PassThru -WindowStyle Normal

    if ($process -and $process.Id) {
        Write-Log "Unity launched successfully (PID: $($process.Id))"
        Write-Log "Session: Scene=$SceneKey, Duration=${DurationSeconds}s"
        Write-Log "Unity will poll backend at: $BackendUrl/current-session"
        Write-Log "============================================"
        exit 0
    } else {
        Write-Log "ERROR: Failed to start Unity process."
        exit 1
    }
}
catch {
    Write-Log "ERROR: Exception launching Unity: $_"
    Write-Log "Stack: $($_.ScriptStackTrace)"
    exit 1
}
