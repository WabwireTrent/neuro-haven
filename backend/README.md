# Neuro Haven Backend API

This folder contains a simple Express API used by the Unity app for session control.

## Endpoints

- `POST /start-session`
  - Request body (JSON):
    - `scene`: `forest` or `beach`
    - `duration`: integer seconds
- `GET /current-session`
  - Response body (JSON):
    - `scene`
    - `status`
    - `duration`
    - `remainingSeconds`

## Setup

1. Install dependencies from repository root:

```bash
npm install express cors
```

2. Start the API server:

```bash
node backend/server.js
```

3. Use your local machine IP address on Quest, for example:

```csharp
apiBaseUrl = "http://192.168.0.100:3000";
```

## Improvements

- Use WebSockets or Socket.IO for real-time updates instead of polling.
- Add authentication and session locking for production.
- Add persistent database support if you want multi-user tracking.
