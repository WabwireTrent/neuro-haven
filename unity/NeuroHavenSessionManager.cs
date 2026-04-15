using System.Collections;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.SceneManagement;

[DisallowMultipleComponent]
public class NeuroHavenSessionManager : MonoBehaviour
{
    [Header("API Settings")]
    [Tooltip("Use your local machine IP address instead of localhost for Quest devices.")]
    public string apiBaseUrl = "http://192.168.0.100:3000";

    [Header("Polling")]
    [Tooltip("API polling interval in seconds.")]
    public float pollInterval = 3f;

    [Header("Scene Mapping")]
    public string forestSceneName = "ForestScene";
    public string beachSceneName = "BeachScene";

    [Header("Audio")]
    public AudioClip forestAudio;
    public AudioClip beachAudio;

    [Header("Fade Transition")]
    [Range(0.1f, 3f)]
    public float fadeDuration = 1f;
    [Tooltip("Optional CanvasGroup used for fade effects.")]
    public CanvasGroup fadeCanvasGroup;

    private string currentSceneKey;
    private bool isTransitioning;
    private AudioSource audioSource;
    private SessionData currentSession;
    private float sessionTimer;

    private void Awake()
    {
        audioSource = gameObject.AddComponent<AudioSource>();
        audioSource.loop = true;
        audioSource.playOnAwake = false;
        currentSession = new SessionData();
        currentSceneKey = string.Empty;
    }

    private void Start()
    {
        StartCoroutine(PollCurrentSession());
    }

    private void Update()
    {
        if (currentSession != null && currentSession.status == "running")
        {
            sessionTimer += Time.deltaTime;
            if (sessionTimer >= currentSession.duration)
            {
                Debug.Log("[NeuroHaven] Session has ended.");
                currentSession.status = "ended";
                sessionTimer = currentSession.duration;
            }
        }
    }

    private IEnumerator PollCurrentSession()
    {
        while (true)
        {
            yield return GetCurrentSession();
            yield return new WaitForSeconds(pollInterval);
        }
    }

    private IEnumerator GetCurrentSession()
    {
        var requestUrl = $"{apiBaseUrl.TrimEnd('/')}/current-session";
        using var request = UnityWebRequest.Get(requestUrl);
        request.SetRequestHeader("Accept", "application/json");

        Debug.Log($"[NeuroHaven] Polling session API: {requestUrl}");
        yield return request.SendWebRequest();

        if (request.result != UnityWebRequest.Result.Success)
        {
            Debug.LogError($"[NeuroHaven] Failed to fetch current session: {request.error}");
            yield break;
        }

        var json = request.downloadHandler.text;
        currentSession = JsonUtility.FromJson<SessionData>(json);
        Debug.Log($"[NeuroHaven] Received session: scene={currentSession.scene}, status={currentSession.status}, duration={currentSession.duration}, remaining={currentSession.remainingSeconds}");

        if (currentSession.status == "start" || currentSession.status == "running")
        {
            HandleSessionStart();
        }
        else if (currentSession.status == "ended")
        {
            Debug.Log("[NeuroHaven] Session ended state detected.");
        }
    }

    private void HandleSessionStart()
    {
        if (isTransitioning)
            return;

        if (string.IsNullOrEmpty(currentSession.scene))
            return;

        if (currentSceneKey == currentSession.scene &&
            (currentSession.status == "start" || currentSession.status == "running"))
        {
            return;
        }

        if (currentSession.status == "start")
        {
            currentSession.status = "running";
        }

        Debug.Log($"[NeuroHaven] Starting scene: {currentSession.scene}");
        StartCoroutine(TransitionToScene(currentSession.scene));

        sessionTimer = 0f;
    }

    private IEnumerator TransitionToScene(string sceneKey)
    {
        isTransitioning = true;
        yield return StartCoroutine(FadeOut());

        var sceneName = MapSceneKeyToBuildName(sceneKey);
        if (!string.IsNullOrEmpty(sceneName))
        {
            Debug.Log($"[NeuroHaven] Loading scene: {sceneName}");
            SceneManager.LoadScene(sceneName);
            currentSceneKey = sceneKey;
            PlaySceneAudio(sceneKey);
        }
        else
        {
            Debug.LogWarning($"[NeuroHaven] Unknown scene mapping for key: {sceneKey}");
        }

        yield return StartCoroutine(FadeIn());
        isTransitioning = false;
    }

    private string MapSceneKeyToBuildName(string sceneKey)
    {
        return sceneKey switch
        {
            "forest" => forestSceneName,
            "beach" => beachSceneName,
            _ => string.Empty,
        };
    }

    private void PlaySceneAudio(string sceneKey)
    {
        switch (sceneKey)
        {
            case "forest":
                audioSource.clip = forestAudio;
                break;
            case "beach":
                audioSource.clip = beachAudio;
                break;
            default:
                audioSource.clip = null;
                break;
        }

        if (audioSource.clip != null)
        {
            audioSource.Play();
            Debug.Log($"[NeuroHaven] Playing audio for scene: {sceneKey}");
        }
        else
        {
            audioSource.Stop();
            Debug.Log($"[NeuroHaven] No audio assigned for scene: {sceneKey}");
        }
    }

    private IEnumerator FadeOut()
    {
        if (fadeCanvasGroup == null)
        {
            yield return new WaitForSeconds(fadeDuration);
            yield break;
        }

        var elapsed = 0f;
        while (elapsed < fadeDuration)
        {
            elapsed += Time.deltaTime;
            fadeCanvasGroup.alpha = Mathf.Clamp01(elapsed / fadeDuration);
            yield return null;
        }

        fadeCanvasGroup.alpha = 1f;
    }

    private IEnumerator FadeIn()
    {
        if (fadeCanvasGroup == null)
        {
            yield return new WaitForSeconds(fadeDuration);
            yield break;
        }

        var elapsed = 0f;
        while (elapsed < fadeDuration)
        {
            elapsed += Time.deltaTime;
            fadeCanvasGroup.alpha = 1f - Mathf.Clamp01(elapsed / fadeDuration);
            yield return null;
        }

        fadeCanvasGroup.alpha = 0f;
    }

    [System.Serializable]
    private class SessionData
    {
        public string scene;
        public string status;
        public int duration;
        public int remainingSeconds;
    }
}
