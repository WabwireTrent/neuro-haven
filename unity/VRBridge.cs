using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.SceneManagement;
using System;

[DisallowMultipleComponent]
public class VRBridge : MonoBehaviour
{
    [Header("Backend API")]
    [Tooltip("URL of the Node.js backend server.")]
    public string backendUrl = "http://localhost:3000";

    [Header("Laravel API")]
    [Tooltip("URL of the Laravel backend.")]
    public string laravelUrl = "http://localhost:8000";

    [Header("Scene Configuration")]
    public string forestSceneName = "ForestScene";
    public string beachSceneName = "BeachScene";
    public string mountainSceneName = "MountainScene";
    public string breathingSceneName = "BreathingScene";

    [Header("XR Settings")]
    [Tooltip("Automatically enable XR on start if headset is connected.")]
    public bool autoEnableXR = true;

    private string currentSceneKey;
    private bool sessionActive;
    private float sessionTimer;
    private int sessionDuration;
    private int currentSessionId;
    private bool useDirectStart;

    private void Awake()
    {
        ParseCommandLineArgs();
    }

    private void ParseCommandLineArgs()
    {
        string[] args = Environment.GetCommandLineArgs();
        for (int i = 0; i < args.Length; i++)
        {
            if (args[i] == "-sceneKey" && i + 1 < args.Length)
            {
                currentSceneKey = args[i + 1];
                useDirectStart = true;
                Debug.Log($"[VRBridge] Command line sceneKey: {currentSceneKey}");
            }
            else if (args[i] == "-durationSeconds" && i + 1 < args.Length)
            {
                int.TryParse(args[i + 1], out sessionDuration);
                Debug.Log($"[VRBridge] Command line duration: {sessionDuration}s");
            }
            else if (args[i] == "-backendUrl" && i + 1 < args.Length)
            {
                backendUrl = args[i + 1];
                Debug.Log($"[VRBridge] Command line backend URL: {backendUrl}");
            }
            else if (args[i] == "-laravelUrl" && i + 1 < args.Length)
            {
                laravelUrl = args[i + 1];
                Debug.Log($"[VRBridge] Command line Laravel URL: {laravelUrl}");
            }
        }
    }

    private IEnumerator Start()
    {
        if (useDirectStart && !string.IsNullOrEmpty(currentSceneKey))
        {
            yield return StartCoroutine(StartDirectSession(currentSceneKey, sessionDuration));
        }
        else
        {
            Debug.Log("[VRBridge] No direct start requested. Waiting for polled session.");
            var manager = GetComponent<NeuroHavenSessionManager>();
            if (manager != null)
            {
                manager.apiBaseUrl = backendUrl;
                manager.enabled = true;
            }
            else
            {
                Debug.LogWarning("[VRBridge] No NeuroHavenSessionManager found. Starting poll coroutine directly.");
                StartCoroutine(PollForSession());
            }
        }
    }

    private IEnumerator StartDirectSession(string sceneKey, int durationSeconds)
    {
        Debug.Log($"[VRBridge] Starting direct session: scene={sceneKey}, duration={durationSeconds}s");

        yield return StartCoroutine(LoadScene(sceneKey));

        sessionActive = true;
        sessionTimer = 0f;

        Debug.Log($"[VRBridge] Direct session started. Duration: {durationSeconds}s");

        while (sessionTimer < durationSeconds)
        {
            sessionTimer += Time.deltaTime;
            yield return null;
        }

        Debug.Log("[VRBridge] Direct session completed.");
        sessionActive = false;

        yield return StartCoroutine(NotifySessionComplete(sceneKey, durationSeconds));
    }

    private IEnumerator LoadScene(string sceneKey)
    {
        string sceneName = MapSceneKeyToBuildName(sceneKey);
        if (!string.IsNullOrEmpty(sceneName))
        {
            Debug.Log($"[VRBridge] Loading scene: {sceneName}");
            var asyncOp = SceneManager.LoadSceneAsync(sceneName);
            while (!asyncOp.isDone)
            {
                yield return null;
            }
            currentSceneKey = sceneKey;
        }
        else
        {
            Debug.LogWarning($"[VRBridge] Unknown scene key: {sceneKey}");
        }
    }

    private IEnumerator PollForSession()
    {
        var pollInterval = 3f;
        while (true)
        {
            var requestUrl = $"{backendUrl.TrimEnd('/')}/current-session";
            using var request = UnityWebRequest.Get(requestUrl);
            request.SetRequestHeader("Accept", "application/json");

            yield return request.SendWebRequest();

            if (request.result == UnityWebRequest.Result.Success)
            {
                var json = request.downloadHandler.text;
                var session = JsonUtility.FromJson<SessionResponse>(json);

                if ((session.status == "start" || session.status == "running") && !sessionActive)
                {
                    currentSessionId = session.sessionId;
                    sessionDuration = session.duration;
                    yield return StartCoroutine(LoadScene(session.scene));
                    sessionActive = true;
                    sessionTimer = 0f;
                }
                else if (session.status == "ended" && sessionActive)
                {
                    Debug.Log("[VRBridge] Session ended via API.");
                    sessionActive = false;
                }
            }

            if (sessionActive)
            {
                sessionTimer += pollInterval;
                if (sessionTimer >= sessionDuration)
                {
                    Debug.Log("[VRBridge] Session duration reached.");
                    sessionActive = false;
                    yield return StartCoroutine(NotifySessionComplete(currentSceneKey, (int)sessionTimer));
                }
            }

            yield return new WaitForSeconds(pollInterval);
        }
    }

    private IEnumerator NotifySessionComplete(string sceneKey, int durationSeconds)
    {
        var form = new WWWForm();
        form.AddField("scene", sceneKey);
        form.AddField("duration", durationSeconds.ToString());

        using var request = UnityWebRequest.Post($"{backendUrl.TrimEnd('/')}/end-session", form);
        yield return request.SendWebRequest();

        if (request.result == UnityWebRequest.Result.Success)
        {
            Debug.Log("[VRBridge] Session completion sent to backend.");
        }
        else
        {
            Debug.LogWarning($"[VRBridge] Failed to notify backend: {request.error}");
        }
    }

    private string MapSceneKeyToBuildName(string sceneKey)
    {
        return sceneKey switch
        {
            "forest" => forestSceneName,
            "beach" => beachSceneName,
            "mountain" => mountainSceneName,
            "breathing" => breathingSceneName,
            _ => string.Empty,
        };
    }

    [System.Serializable]
    private class SessionResponse
    {
        public string scene;
        public string status;
        public int duration;
        public int remainingSeconds;
        public string displayName;
        public int sessionId;
        public int userId;
    }

    public bool IsSessionActive()
    {
        return sessionActive;
    }

    public float GetSessionProgress()
    {
        if (sessionDuration <= 0) return 0f;
        return Mathf.Clamp01(sessionTimer / sessionDuration);
    }

    public string GetCurrentSceneKey()
    {
        return currentSceneKey;
    }
}
