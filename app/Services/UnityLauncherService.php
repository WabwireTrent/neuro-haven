<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class UnityLauncherService
{
    protected string $unityPath;
    protected string $backendUrl;
    protected ?int $lastPid = null;

    public function __construct()
    {
        $this->unityPath = config('services.unity.executable_path', '');
        $this->backendUrl = config('services.backend.url', 'http://localhost:3000');
    }

    public function launch(string $sceneKey, int $durationSeconds, array $args = []): array
    {
        if (empty($this->unityPath)) {
            return [
                'success' => false,
                'error' => 'Unity executable path not configured. Set UNITY_EXECUTABLE_PATH in .env',
            ];
        }

        if (!file_exists($this->unityPath)) {
            return [
                'success' => false,
                'error' => "Unity executable not found at: {$this->unityPath}",
            ];
        }

        $sceneArg = "-sceneKey {$sceneKey}";
        $durationArg = "-durationSeconds {$durationSeconds}";
        $backendArg = "-backendUrl {$this->backendUrl}";

        $cmd = "\"{$this->unityPath}\" {$sceneArg} {$durationArg} {$backendArg}";

        $logFile = storage_path("logs/unity-launch-" . now()->format('Ymd-His') . ".log");

        $wrappedCmd = "cmd /c START \"NeuroHavenUnity\" /B {$cmd} > \"{$logFile}\" 2>&1";

        Log::info("[UnityLauncher] Launching Unity: {$cmd}");

        $output = [];
        $returnCode = 0;
        exec($wrappedCmd, $output, $returnCode);

        if ($returnCode !== 0) {
            Log::error("[UnityLauncher] Failed to launch Unity. Return code: {$returnCode}");
            return [
                'success' => false,
                'error' => "Failed to launch Unity process. Return code: {$returnCode}",
            ];
        }

        Log::info("[UnityLauncher] Unity launched successfully");

        return [
            'success' => true,
            'command' => $cmd,
            'log_file' => $logFile,
            'started_at' => now()->toIso8601String(),
        ];
    }

    public function launchViaPowerShell(string $sceneKey, int $durationSeconds, array $args = []): array
    {
        $scriptPath = base_path('scripts/launch-unity.ps1');

        if (!file_exists($scriptPath)) {
            return $this->launch($sceneKey, $durationSeconds, $args);
        }

        $unityPath = $this->unityPath;
        $backendUrl = $this->backendUrl;
        $sceneArg = $sceneKey;
        $durationArg = (string) $durationSeconds;

        $psCmd = "powershell -ExecutionPolicy Bypass -File \"{$scriptPath}\" -UnityPath \"{$unityPath}\" -SceneKey \"{$sceneArg}\" -DurationSeconds {$durationArg} -BackendUrl \"{$backendUrl}\"";

        $logFile = storage_path("logs/unity-launch-" . now()->format('Ymd-His') . ".log");

        $wrappedCmd = "cmd /c START \"NeuroHavenUnity\" /B {$psCmd} > \"{$logFile}\" 2>&1";

        Log::info("[UnityLauncher] Launching Unity via PowerShell: {$psCmd}");

        $output = [];
        $returnCode = 0;
        exec($wrappedCmd, $output, $returnCode);

        if ($returnCode !== 0) {
            Log::error("[UnityLauncher] PowerShell launch failed. Return code: {$returnCode}");
            return [
                'success' => false,
                'error' => "Failed to launch Unity via PowerShell. Return code: {$returnCode}",
            ];
        }

        return [
            'success' => true,
            'method' => 'powershell',
            'log_file' => $logFile,
            'started_at' => now()->toIso8601String(),
        ];
    }

    public function isRunning(): bool
    {
        if ($this->lastPid === null) {
            return false;
        }

        $output = [];
        exec("tasklist /FI \"PID eq {$this->lastPid}\" 2>NUL", $output);
        foreach ($output as $line) {
            if (str_contains($line, (string) $this->lastPid)) {
                return true;
            }
        }

        $this->lastPid = null;
        return false;
    }

    public function setUnityPath(string $path): void
    {
        $this->unityPath = $path;
    }

    public function getUnityPath(): string
    {
        return $this->unityPath;
    }
}
