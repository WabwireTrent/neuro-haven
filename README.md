# Neuro Haven

## Problem & Solution

Mental health conditions such as anxiety, depression, PTSD, and stress disorders affect millions of people worldwide, yet access to effective, personalised therapy remains limited. Traditional therapy relies heavily on in-person sessions with a therapist, which can be constrained by cost, availability, stigma, and geographical barriers. Patients often struggle to maintain consistent therapeutic routines between sessions, and therapists lack tools to monitor patient progress in real time.

**Neuro Haven** addresses these challenges by providing an integrated mental health therapy platform that combines:

- **Web-Based Mental Health Management** — Patients and therapists interact through a comprehensive web dashboard featuring psychological assessments (PHQ-9, GAD-7, PCL-5, AUDIT-C, BAI), mood tracking, treatment plans, session scheduling, progress analytics, crisis alerts, and badge-based gamification to encourage engagement.

- **Virtual Reality Therapeutic Experiences** — The platform bridges to Unity-based VR environments (forest walks, ocean meditation, mountain scenery, guided breathing exercises) rendered on Meta Quest headsets. VR therapy provides immersive, controlled environments proven to reduce anxiety, manage pain, and support exposure therapy — all accessible from the patient's location.

- **Therapist-Patient Collaboration** — Therapists assign patients, create personalised treatment plans with suggested therapy sessions, monitor assessment scores and mood trends over time, and receive crisis alerts when patients flag urgent distress.

- **Real-Time Integration** — A Node.js backend bridges the Laravel web application with the Unity VR application, enabling seamless session launching, status monitoring, and data collection from both web and VR modalities.

The result is a unified platform where therapists can prescribe VR experiences as part of treatment plans, patients can engage in self-guided or therapist-directed VR therapy, and both parties have access to continuous progress data — making mental health care more accessible, engaging, and effective.

---

## Setup Instructions

### Prerequisites

| Requirement | Version |
|-------------|---------|
| PHP | 8.3+ |
| Composer | Latest |
| Node.js | 18+ |
| npm | Latest |
| SQLite | (bundled with PHP) |
| Unity | 2022.3+ LTS (for VR features) |
| Meta Quest | Quest 2 / Quest 3 / Quest Pro (for VR features) |

### Step 1: Clone the Repository

```bash
git clone https://github.com/WabwireTrent/neuro-haven.git
cd neuro-haven
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

### Step 4: Configure Environment

```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env` and set the following as needed:

```env
APP_NAME="Neuro Haven"
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
BACKEND_URL=http://localhost:3000

# Unity VR settings (optional, for VR features only)
UNITY_EXECUTABLE_PATH=C:\NeuroHaven\Builds\NeuroHavenVR.exe
```

### Step 5: Set Up the Database

```bash
touch database/database.sqlite
php artisan migrate --force
```

To seed the database with sample VR assets and test data:

```bash
php artisan db:seed
```

### Step 6: Link Storage

```bash
php artisan storage:link
```

### Step 7: Build Frontend Assets

```bash
npm run build
```

### Step 8: Start the Application

**Terminal 1 — Laravel Server:**

```bash
php artisan serve --port=8000
```

**Terminal 2 — Node.js Backend Bridge (required for VR integration):**

```bash
cd backend
npm install
npm start
```

**Terminal 3 — Vite Dev Server (for development with hot reload):**

```bash
npm run dev
```

### Step 9: Access the Application

Open your browser and navigate to:

```
http://localhost:8000
```

Register an account or log in to access the dashboard, assessments, mood tracker, VR library, and therapist tools.

### VR Setup (Optional)

If you want to use the VR therapy features with a Meta Quest headset:

1. Open the Unity project in `unity/` with Unity 2022.3+ LTS
2. Install OpenXR Plugin and XR Interaction Toolkit via Unity Package Manager
3. Import `NeuroHavenSessionManager.cs` and `VRBridge.cs` into your Unity project
4. Build the project for Windows (x86_64)
5. Connect your Meta Quest via USB Link or Air Link
6. Set `UNITY_EXECUTABLE_PATH` in `.env` to point to the built `.exe`

### Project Structure

```
neuro-haven/
├── app/
│   ├── Http/Controllers/      # Web & API controllers
│   ├── Models/                # Eloquent models (User, Assessment, VRAsset, etc.)
│   └── Services/              # Notification & Unity launcher services
├── backend/
│   └── server.js              # Node.js bridge server for VR session management
├── config/                    # Laravel configuration
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Sample data seeders
├── resources/
│   └── views/                 # Blade templates (dashboard, assessments, VR, etc.)
├── routes/
│   └── web.php                # Route definitions
├── scripts/
│   └── launch-unity.ps1       # PowerShell script to launch Unity builds
├── unity/
│   ├── NeuroHavenSessionManager.cs  # Unity session polling & scene switching
│   └── VRBridge.cs            # Unity CLI argument parsing
└── tests/                     # PHPUnit tests
```
