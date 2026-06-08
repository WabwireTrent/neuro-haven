import express from 'express';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';
import cors from 'cors';
import { WebSocketServer } from 'ws';
import http from 'http';

const app = express();
const server = http.createServer(app);
const wss = new WebSocketServer({ server });

const PORT = process.env.PORT || 3000;
const ALLOWED_SCENES = ['forest', 'beach', 'mountain', 'breathing'];

const SCENE_DISPLAY_NAMES = {
  forest: 'Peaceful Forest Walk',
  beach: 'Ocean Meditation',
  mountain: 'Mountain View Therapy',
  breathing: 'Guided Breathing Exercise',
};
const __dirname = path.dirname(fileURLToPath(import.meta.url));
const STORE_FILE = path.join(__dirname, 'session-store.json');

let currentSession = null;
const connectedClients = new Map(); // Map<userId, Set<WebSocket>>

// WebSocket connection handling
wss.on('connection', (ws) => {
  console.log('[WebSocket] New client connected');

  // Set up a ping interval to detect dead connections
  ws.isAlive = true;
  ws.on('pong', () => { ws.isAlive = true; });

  ws.on('message', (message) => {
    try {
      const data = JSON.parse(message);
      
      if (data.type === 'auth') {
        const userId = data.userId;
        if (!connectedClients.has(userId)) {
          connectedClients.set(userId, new Set());
        }
        connectedClients.get(userId).add(ws);
        console.log(`[WebSocket] User ${userId} authenticated`);
        ws.userId = userId;
        // Acknowledge auth
        ws.send(JSON.stringify({ type: 'auth_ack', status: 'ok' }));
      }
    } catch (error) {
      console.error('[WebSocket] Failed to parse message:', error);
    }
  });

  ws.on('error', (error) => {
    console.error('[WebSocket] Client error:', error.message);
  });

  ws.on('close', () => {
    if (ws.userId) {
      const clients = connectedClients.get(ws.userId);
      if (clients) {
        clients.delete(ws);
        if (clients.size === 0) {
          connectedClients.delete(ws.userId);
        }
      }
    }
    console.log('[WebSocket] Client disconnected');
  });
});

// Heartbeat interval — remove dead connections every 30 seconds
const heartbeatInterval = setInterval(() => {
  wss.clients.forEach((ws) => {
    if (!ws.isAlive) {
      console.log('[WebSocket] Terminating dead connection');
      return ws.terminate();
    }
    ws.isAlive = false;
    ws.ping();
  });
}, 30000);

wss.on('close', () => {
  clearInterval(heartbeatInterval);
});

// Broadcast notification to a specific user
function broadcastToUser(userId, notification) {
  const clients = connectedClients.get(userId);
  if (clients && clients.size > 0) {
    const message = JSON.stringify(notification);
    clients.forEach(client => {
      if (client.readyState === 1) { // WebSocket.OPEN
        client.send(message);
      } else {
        clients.delete(client);
      }
    });
  }
}

async function loadSessionFromFile() {
  try {
    const data = await fs.readFile(STORE_FILE, 'utf-8');
    const parsed = JSON.parse(data);
    currentSession = parsed.currentSession || null;
    console.log('[backend] Loaded session from file:', currentSession);
  } catch (error) {
    console.warn('[backend] No existing session-store.json found or invalid JSON, using empty session.');
    currentSession = null;
  }
}

async function saveSessionToFile() {
  try {
    const payload = { currentSession };
    await fs.writeFile(STORE_FILE, JSON.stringify(payload, null, 2), 'utf-8');
  } catch (error) {
    console.error('[backend] Failed to write session-store.json:', error);
  }
}

app.use(cors());
app.use(express.json());

app.post('/start-session', async (req, res) => {
  const { scene, duration, session_id, user_id } = req.body;

  if (!scene || typeof scene !== 'string') {
    return res.status(400).json({ error: 'scene is required and must be a string.' });
  }

  if (!ALLOWED_SCENES.includes(scene)) {
    return res.status(400).json({
      error: `scene must be one of: ${ALLOWED_SCENES.join(', ')}.`,
    });
  }

  if (duration === undefined || typeof duration !== 'number' || !Number.isFinite(duration)) {
    return res.status(400).json({ error: 'duration is required and must be a number.' });
  }

  if (!Number.isInteger(duration) || duration <= 0) {
    return res.status(400).json({ error: 'duration must be a positive integer representing seconds.' });
  }

  currentSession = {
    scene,
    status: 'start',
    duration,
    startedAt: Date.now(),
    sessionId: session_id || null,
    userId: user_id || null,
    displayName: SCENE_DISPLAY_NAMES[scene] || scene,
  };

  await saveSessionToFile();

  return res.status(201).json({
    message: 'Session started successfully.',
    currentSession,
  });
});

app.get('/current-session', (req, res) => {
  if (!currentSession) {
    return res.status(200).json({
      scene: null,
      status: 'idle',
      duration: 0,
      remainingSeconds: 0,
      displayName: null,
      sessionId: null,
      userId: null,
    });
  }

  const elapsedSeconds = Math.floor((Date.now() - currentSession.startedAt) / 1000);
  const remaining = Math.max(0, currentSession.duration - elapsedSeconds);

  let status = currentSession.status;
  if (status === 'start' && elapsedSeconds > 0) {
    status = 'running';
  }

  if (remaining === 0) {
    status = 'ended';
  }

  return res.status(200).json({
    scene: currentSession.scene,
    status,
    duration: currentSession.duration,
    remainingSeconds: remaining,
    displayName: currentSession.displayName || currentSession.scene,
    sessionId: currentSession.sessionId || null,
    userId: currentSession.userId || null,
  });
});

// Broadcast notification endpoint (called from Laravel)
app.post('/api/broadcast-notification', (req, res) => {
  try {
    const { user_id, notification_id, type, title, message, severity, data } = req.body;
    
    if (!user_id) {
      return res.status(400).json({ error: 'user_id is required' });
    }

    const notification = {
      id: notification_id,
      type,
      title,
      message,
      severity,
      data,
      timestamp: new Date().toISOString()
    };

    broadcastToUser(user_id, notification);

    return res.status(200).json({
      message: 'Notification broadcasted',
      notification
    });
  } catch (error) {
    console.error('[backend] Broadcast error:', error);
    return res.status(500).json({ error: 'Failed to broadcast notification' });
  }
});

app.get('/end-session', async (req, res) => {
  if (!currentSession) {
    return res.status(200).json({
      message: 'No active session.',
    });
  }

  const endedSession = currentSession;
  currentSession = null;

  await saveSessionToFile();

  return res.status(200).json({
    message: 'Session ended successfully.',
    endedSession,
  });
});

app.use((err, req, res, next) => {
  console.error('[backend] Unexpected error:', err);
  return res.status(500).json({ error: 'Internal server error.' });
});

// Graceful shutdown
process.on('SIGTERM', () => {
  console.log('[backend] SIGTERM received, shutting down gracefully...');
  server.close(() => {
    console.log('[backend] HTTP server closed.');
    process.exit(0);
  });
});

process.on('SIGINT', () => {
  console.log('[backend] SIGINT received, shutting down gracefully...');
  server.close(() => {
    console.log('[backend] HTTP server closed.');
    process.exit(0);
  });
});

await loadSessionFromFile();

server.listen(PORT, () => {
  console.log(`[backend] Neuro Haven API listening on http://0.0.0.0:${PORT}`);
  console.log(`[backend] WebSocket server ready for connections`);
  console.log('[backend] Use your local machine IP address instead of localhost when connecting from Quest.');
});
