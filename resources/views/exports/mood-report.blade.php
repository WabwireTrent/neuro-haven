<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mood Report - Neuro Haven</title>
  <style>
    @page { margin: 20mm 15mm; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11pt; color: #1e293b; line-height: 1.6; }
    h1 { font-size: 18pt; margin: 0 0 4pt; color: #0f172a; }
    h2 { font-size: 14pt; margin: 20pt 0 8pt; color: #0f172a; border-bottom: 2px solid #3b82f6; padding-bottom: 4pt; }
    h3 { font-size: 11pt; margin: 14pt 0 6pt; color: #334155; }
    .header { text-align: center; padding-bottom: 14pt; border-bottom: 3px solid #3b82f6; margin-bottom: 16pt; }
    .header p { color: #64748b; font-size: 9pt; margin: 4pt 0 0; }
    .stats { display: flex; justify-content: space-between; margin: 12pt 0; }
    .stat-box { text-align: center; padding: 8pt 12pt; background: #f1f5f9; border-radius: 6px; flex: 1; margin: 0 4pt; }
    .stat-box .num { font-size: 16pt; font-weight: 800; color: #3b82f6; }
    .stat-box .lbl { font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2pt; }
    table { width: 100%; border-collapse: collapse; margin: 10pt 0; font-size: 9pt; }
    th { background: #3b82f6; color: #fff; padding: 6pt 8pt; text-align: left; font-weight: 700; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.04em; }
    td { padding: 5pt 8pt; border-bottom: 1px solid #e2e8f0; }
    tr:nth-child(even) td { background: #f8fafc; }
    .mood-bar { display: inline-block; height: 8px; border-radius: 4px; min-width: 20px; }
    .footer { text-align: center; color: #94a3b8; font-size: 7pt; margin-top: 24pt; border-top: 1px solid #e2e8f0; padding-top: 10pt; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 7pt; font-weight: 700; }
    .badge-green { background: #dcfce7; color: #16a34a; }
    .badge-yellow { background: #fef9c3; color: #ca8a04; }
    .badge-red { background: #fee2e2; color: #dc2626; }
  </style>
</head>
<body>

<div class="header">
  <h1>Neuro Haven — Mood Report</h1>
  <p>Generated {{ now()->format('F d, Y \a\t g:i A') }} &middot; {{ $user->name }} &middot; {{ $periodLabel }}</p>
</div>

<div class="stats">
  <div class="stat-box">
    <div class="num">{{ number_format($avgMood, 1) }}</div>
    <div class="lbl">Avg Mood</div>
  </div>
  <div class="stat-box">
    <div class="num">{{ $totalEntries }}</div>
    <div class="lbl">Total Entries</div>
  </div>
  <div class="stat-box">
    <div class="num">{{ $streak }}</div>
    <div class="lbl">Day Streak</div>
  </div>
  <div class="stat-box">
    <div class="num">{{ $sessions->count() }}</div>
    <div class="lbl">VR Sessions</div>
  </div>
</div>

<h2>Mood History ({{ $periodLabel }})</h2>

<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Mood</th>
      <th>Intensity</th>
      <th>Notes</th>
    </tr>
  </thead>
  <tbody>
    @forelse($moods as $mood)
      <tr>
        <td>{{ $mood->mood_date->format('M d, Y') }}</td>
        <td><span class="badge badge-{{ $mood->mood_scale <= 3 ? 'red' : ($mood->mood_scale <= 6 ? 'yellow' : 'green') }}">{{ ucfirst($mood->mood) }}</span></td>
        <td>
          <div style="display:flex;align-items:center;gap:6px;">
            <div class="mood-bar" style="width:{{ ($mood->mood_scale / 10) * 60 }}px;background:{{ $mood->mood_scale <= 3 ? '#ef4444' : ($mood->mood_scale <= 6 ? '#eab308' : '#22c55e') }};"></div>
            {{ $mood->mood_scale }}/10
          </div>
        </td>
        <td style="color:#64748b;max-width:180px;">{{ $mood->note ? Str::limit($mood->note, 60) : '—' }}</td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align:center;color:#94a3b8;">No mood entries recorded yet.</td></tr>
    @endforelse
  </tbody>
</table>

<h2>VR Sessions Summary</h2>

<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Environment</th>
      <th>Duration</th>
      <th>Quality</th>
    </tr>
  </thead>
  <tbody>
    @forelse($sessions as $session)
      <tr>
        <td>{{ $session->started_at ? $session->started_at->format('M d, Y') : '—' }}</td>
        <td>{{ $session->vr_asset_title ?? 'Unknown' }}</td>
        <td>{{ $session->session_duration ? round($session->session_duration / 60) . ' min' : '—' }}</td>
        <td>{{ $session->session_quality ? $session->session_quality . '/5' : '—' }}</td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align:center;color:#94a3b8;">No completed VR sessions yet.</td></tr>
    @endforelse
  </tbody>
</table>

<div class="footer">
  Neuro Haven VR Mental Health Platform &middot; This report is for personal reference only.
  <br>Generated on {{ now()->format('F d, Y \a\t h:i A') }}
</div>

</body>
</html>