# Virtual Reality Assets System - Documentation

## Overview
Your Neuro Haven application now has a complete VR assets management system with:
- **Database Storage** for VR therapeutic experiences
- **File Upload** support for images and VR files
- **Admin Panel** for managing assets (Create, Read, Update, Delete)
- **User Interface** to browse and launch VR experiences
- **API Endpoints** for asset filtering and statistics

---

## Features

### 1. **VR Asset Properties**
Each VR asset includes:
- **Title** - Name of the experience
- **Description** - Detailed explanation
- **Category** - Relaxation, Meditation, Inspiration, Breathing, Therapy, Mindfulness, Nature
- **Duration** - Length in minutes (1-120)
- **File Type** - Video, Audio, 3D Model, Interactive
- **Difficulty Level** - 1-5 scale
- **Therapeutic Benefits** - Comma-separated benefits
- **Image/Thumbnail** - Visual representation
- **VR File** - The actual experience file
- **Usage Count** - Tracks how many times it's been used
- **Average Rating** - User ratings (0-5)
- **Status** - Active/Inactive toggle

### 2. **Admin Panel for VR Asset Management**

#### Route: `/admin/vr-assets`

**Admin Capabilities:**
- ✅ View all VR assets in a table format
- ✅ Create new VR assets with file uploads
- ✅ Edit existing assets
- ✅ Delete assets (with file cleanup)
- ✅ Toggle asset active/inactive status
- ✅ Upload thumbnail images (JPEG, PNG, GIF - max 5MB)
- ✅ Upload VR experience files (max 100MB)

**Admin Pages:**
- `/admin/vr-assets` - List all assets
- `/admin/vr-assets/create` - Create new asset
- `/admin/vr-assets/{id}/edit` - Edit asset

### 3. **User Interface**

#### Route: `/vr-assets`

**User Views:**
- Browse all active VR assets
- Filter by category
- View asset details
- Launch VR experiences
- Track usage statistics

#### API Endpoints (Protected):
- `GET /api/vr-assets/category/{category}` - Assets by category
- `GET /api/vr-assets/popular` - Most used assets
- `GET /api/vr-assets/top-rated` - Highest rated assets
- `POST /api/vr-assets/{id}/usage` - Increment usage count

### 4. **Pre-Loaded Sample Assets**

The system comes with 8 sample VR experiences:
1. **Peaceful Forest Walk** - 10 min (Relaxation)
2. **Ocean Meditation** - 15 min (Meditation)
3. **Mountain View Therapy** - 12 min (Inspiration)
4. **Guided Breathing Exercise** - 8 min (Breathing)
5. **Starry Night Relaxation** - 20 min (Relaxation)
6. **Zen Garden Meditation** - 18 min (Meditation)
7. **Tropical Beach Escape** - 15 min (Nature)
8. **Rainforest Mindfulness** - 12 min (Mindfulness)

---

## Database Schema

### `v_r_assets` Table

```sql
- id (Primary Key)
- title (String, 255)
- description (Text, 2000)
- category (String, 100)
- duration_minutes (Integer, 1-120)
- image_path (String, nullable)
- file_path (String, nullable)
- file_type (Enum: video, audio, model, interactive)
- difficulty_level (Integer, 1-5)
- therapeutic_benefits (JSON, nullable)
- is_active (Boolean, default: true)
- usage_count (Integer, default: 0)
- average_rating (Decimal 3,2, default: 0)
- created_at (Timestamp)
- updated_at (Timestamp)
```

---

## File Storage

### Directory Structure
```
storage/app/public/
├── vr-assets/
│   ├── images/     # Thumbnail images for assets
│   └── files/      # VR experience files
```

**Link Storage to Public:**
```bash
php artisan storage:link
```

---

## Model & Controller

### VRAsset Model
**Location:** `app/Models/VRAsset.php`

**Available Scopes:**
```php
VRAsset::active()              // Only active assets
VRAsset::byCategory($cat)      // Filter by category
VRAsset::byDifficulty($level)  // Filter by difficulty
VRAsset::mostPopular(10)       // Order by usage count
VRAsset::highestRated(10)      // Order by rating
```

### VRAssetController
**Location:** `app/Http/Controllers/VRAssetController.php`

**Public Methods:**
- `index()` - Display all active VR assets
- `show(VRAsset)` - Display single asset
- `byCategory($cat)` - Filter by category
- `popular()` - Get most popular assets
- `topRated()` - Get highest rated assets
- `incrementUsage(VRAsset)` - Track usage

**Admin Methods:**
- `adminList()` - List all assets (admin panel)
- `create()` - Show create form
- `store()` - Save new asset
- `edit(VRAsset)` - Show edit form
- `update(VRAsset)` - Save changes
- `destroy(VRAsset)` - Delete asset

---

## Routes

### Protected Routes (Authenticated Users)
```
GET    /vr-assets                      Show all active assets
GET    /vr-assets/{id}                 Show single asset
GET    /api/vr-assets/category/{cat}   Get assets by category
GET    /api/vr-assets/popular          Get most used assets
GET    /api/vr-assets/top-rated        Get highest rated assets
POST   /api/vr-assets/{id}/usage       Track usage
```

### Admin Routes (Authenticated Admins)
```
GET    /admin/vr-assets                List all assets
GET    /admin/vr-assets/create         Create form
POST   /admin/vr-assets                Save new asset
GET    /admin/vr-assets/{id}/edit      Edit form
PUT    /admin/vr-assets/{id}           Save changes
DELETE /admin/vr-assets/{id}           Delete asset
```

---

## How to Use

### For Admins: Add a New VR Asset

1. Go to `/admin/vr-assets`
2. Click **"+ Add New VR Asset"**
3. Fill in the form:
   - Title: Name of the experience
   - Description: What users will experience
   - Category: Select therapeutic category
   - Duration: How long (in minutes)
   - File Type: Video, Audio, 3D Model, or Interactive
   - Difficulty Level: 1-5 scale
   - Therapeutic Benefits: List the benefits
   - Upload thumbnail image (optional)
   - Upload VR file (optional)
   - Toggle "Active" to make it available to users
4. Click **"Create Asset"**

### For Users: Access VR Assets

1. Go to `/vr-assets` (in Dashboard navigation)
2. Browse all available therapeutic experiences
3. View asset details and duration
4. Click "Launch Experience" to start

---

## Configuration

### File Upload Limits
- **Images:** Max 5MB (JPEG, PNG, JPG, GIF)
- **VR Files:** Max 100MB (Video, Audio, 3D Models)

To change these limits, edit `VRAssetController.php` in the `validate()` methods:
```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Change 5120
'file' => 'nullable|file|max:102400', // Change 102400
```

### Storage Disk
The system uses Laravel's `public` disk for storage. Ensure it's linked:
```bash
php artisan storage:link
```

---

## Next Steps

1. **Create Admin Account** - Set at least one user with admin role
2. **Access Admin Panel** - Login and navigate to `/admin/vr-assets`
3. **Upload Assets** - Start adding your therapeutic VR experiences
4. **Test User Portal** - View assets on `/vr-assets` page
5. **Monitor Usage** - Track which assets are most popular
6. **Gather Feedback** - Collect user ratings and improve experiences

---

## Troubleshooting

### Assets Not Showing
- Check that `is_active = true` for the asset
- Verify files are uploaded to `storage/app/public/vr-assets/`
- Clear view cache: `php artisan view:clear`

### File Upload Fails
- Check file size (images: 5MB, files: 100MB)
- Ensure storage directory is writable: `chmod -R 775 storage/`
- Link storage: `php artisan storage:link`

### Admin Panel Not Accessible
- Verify user has 'admin' role
- Check auth middleware in routes
- Verify user is logged in

---

## Next Features to Consider

- User ratings & reviews for assets
- Advanced filtering & search
- Asset usage analytics dashboard
- Integration with VR session tracking
- User recommendations based on mood/history
- Asset categories with featured collections
- Difficulty path recommendations
- Therapist-created custom experiences

---

**Your Neuro Haven VR Assets System is ready to use!**
