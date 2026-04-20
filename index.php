<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AttendOS — Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg:       #0a0a0f;
      --surface:  #111118;
      --border:   #1e1e2e;
      --accent:   #7fff6e;
      --accent2:  #6edfff;
      --danger:   #ff6e6e;
      --warn:     #ffd76e;
      --text:     #e8e8f0;
      --muted:    #5a5a72;
      --radius:   12px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Syne', sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── Sidebar ── */
    .sidebar {
      width: 220px;
      min-height: 100vh;
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      padding: 28px 16px;
      position: fixed;
      top: 0; left: 0;
      z-index: 100;
    }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 40px;
      padding-left: 4px;
    }

    .brand-icon { font-size: 22px; color: var(--accent); filter: drop-shadow(0 0 8px var(--accent)); }
    .brand-text { font-size: 18px; font-weight: 800; letter-spacing: 1px; color: var(--text); }

    .nav-links { list-style: none; flex: 1; }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 14px;
      border-radius: var(--radius);
      color: var(--muted);
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s;
      margin-bottom: 4px;
    }

    .nav-item:hover { background: var(--border); color: var(--text); }
    .nav-item.active { background: rgba(127,255,110,0.08); color: var(--accent); border: 1px solid rgba(127,255,110,0.2); }
    .nav-icon { font-size: 16px; }

    .sidebar-footer {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: var(--muted);
      padding-left: 4px;
    }

    .status-dot {
      width: 8px; height: 8px;
      background: var(--accent);
      border-radius: 50%;
      box-shadow: 0 0 6px var(--accent);
      animation: pulse 2s infinite;
    }

    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    /* ── Main ── */
    .main {
      margin-left: 220px;
      flex: 1;
      padding: 36px 40px;
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .page-header { margin-bottom: 32px; }
    .page-header h1 { font-size: 28px; font-weight: 800; letter-spacing: -0.5px; }
    .page-header p { color: var(--muted); font-size: 14px; margin-top: 4px; font-family: 'DM Mono', monospace; }

    /* ── Stats ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 32px;
    }

    .stat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 22px 24px;
      position: relative;
      overflow: hidden;
      transition: border-color 0.2s;
    }

    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; }
    .stat-card.green::before  { background: var(--accent); }
    .stat-card.blue::before   { background: var(--accent2); }
    .stat-card.red::before    { background: var(--danger); }
    .stat-card.yellow::before { background: var(--warn); }
    .stat-card:hover { border-color: var(--muted); }

    .stat-label { font-size: 11px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 10px; font-family: 'DM Mono', monospace; }
    .stat-value { font-size: 36px; font-weight: 800; line-height: 1; }
    .stat-card.green  .stat-value { color: var(--accent); }
    .stat-card.blue   .stat-value { color: var(--accent2); }
    .stat-card.red    .stat-value { color: var(--danger); }
    .stat-card.yellow .stat-value { color: var(--warn); }
    .stat-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }

    /* ── Card ── */
    .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; margin-bottom: 24px; }

    .card-title {
      font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
      color: var(--muted); margin-bottom: 20px; font-family: 'DM Mono', monospace;
      display: flex; align-items: center; gap: 8px;
    }
    .card-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* ── Form ── */
    .form-row { display: flex; gap: 12px; flex-wrap: wrap; }
    .form-group { flex: 1; min-width: 140px; }
    .form-group label { display: block; font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; font-family: 'DM Mono', monospace; }

    input[type="text"], input[type="number"] {
      width: 100%; background: var(--bg); border: 1px solid var(--border);
      border-radius: 8px; padding: 10px 14px; color: var(--text);
      font-family: 'DM Mono', monospace; font-size: 13px; transition: border-color 0.2s; outline: none;
    }
    input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(127,255,110,0.08); }

    .btn { padding: 10px 24px; border: none; border-radius: 8px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s; letter-spacing: 0.5px; }
    .btn-primary { background: var(--accent); color: #0a0a0f; }
    .btn-primary:hover { background: #9fff91; box-shadow: 0 0 20px rgba(127,255,110,0.3); }
    .btn-success { background: var(--accent2); color: #0a0a0f; }
    .btn-success:hover { background: #91eeff; box-shadow: 0 0 20px rgba(110,223,255,0.3); }

    /* ── Table ── */
    .attend-table { width: 100%; border-collapse: collapse; }
    .attend-table th { font-size: 11px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); padding: 10px 16px; text-align: left; font-family: 'DM Mono', monospace; border-bottom: 1px solid var(--border); }
    .attend-table td { padding: 14px 16px; border-bottom: 1px solid var(--border); font-size: 14px; }
    .attend-table tr:last-child td { border-bottom: none; }
    .attend-table tr:hover td { background: rgba(255,255,255,0.02); }

    .roll-badge { background: var(--border); color: var(--accent2); padding: 3px 10px; border-radius: 6px; font-family: 'DM Mono', monospace; font-size: 12px; font-weight: 500; }

    /* ── Radio ── */
    .radio-group { display: flex; gap: 6px; }
    .radio-label { cursor: pointer; }
    .radio-label input[type="radio"] { display: none; }
    .radio-btn { display: inline-block; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; border: 1px solid var(--border); color: var(--muted); transition: all 0.15s; font-family: 'DM Mono', monospace; }
    .radio-label input:checked + .radio-btn.p { background: rgba(127,255,110,0.15); border-color: var(--accent); color: var(--accent); }
    .radio-label input:checked + .radio-btn.a { background: rgba(255,110,110,0.15); border-color: var(--danger); color: var(--danger); }
    .radio-label input:checked + .radio-btn.o { background: rgba(110,223,255,0.15); border-color: var(--accent2); color: var(--accent2); }
    .radio-label input:checked + .radio-btn.l { background: rgba(255,215,110,0.15); border-color: var(--warn); color: var(--warn); }
    .radio-btn:hover { border-color: var(--muted); color: var(--text); }

    .alert { padding: 12px 18px; border-radius: var(--radius); font-size: 13px; margin-bottom: 20px; font-family: 'DM Mono', monospace; }
    .alert-success { background: rgba(127,255,110,0.08); border: 1px solid rgba(127,255,110,0.2); color: var(--accent); }

    .empty-state { text-align: center; padding: 48px; color: var(--muted); }
    .empty-state .icon { font-size: 40px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }

    /* ── MOBILE ── */
    @media (max-width: 768px) {
      body { flex-direction: column; }

      .sidebar {
        width: 100%;
        min-height: auto;
        position: relative;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 14px 16px;
        border-right: none;
        border-bottom: 1px solid var(--border);
        align-items: center;
        justify-content: space-between;
      }

      .sidebar-brand { margin-bottom: 0; }
      .sidebar-footer { display: none; }

      .nav-links {
        display: flex;
        flex-direction: row;
        gap: 8px;
        width: 100%;
        margin-top: 10px;
      }

      .nav-item { padding: 8px 14px; font-size: 13px; margin-bottom: 0; }

      .main { margin-left: 0; padding: 16px; }

      .page-header h1 { font-size: 22px; }

      .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }
      .stat-card { padding: 16px; }
      .stat-value { font-size: 28px; }

      .card { padding: 16px; }

      .form-row { flex-direction: column; gap: 10px; }
      .form-group { min-width: 100% !important; }

      .attend-table { font-size: 12px; display: block; overflow-x: auto; }
      .attend-table th, .attend-table td { padding: 10px 8px; }

      .radio-btn { padding: 4px 8px; font-size: 10px; }

      .btn { width: 100%; text-align: center; margin-top: 8px; }
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main">
  <div class="page-header">
    <h1>Dashboard</h1>
    <p><?= date('l, d M Y') ?> &nbsp;·&nbsp; College Attendance System</p>
  </div>

  <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">✓ &nbsp;<?= htmlspecialchars($_GET['success']) ?></div>
  <?php endif; ?>

  <?php
    $total_students = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM students3"))[0];
    $today = date('Y-m-d');
    $present_today = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM attendance3 WHERE date='$today' AND status='Present'"))[0];
    $absent_today  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM attendance3 WHERE date='$today' AND status='Absent'"))[0];
    $marked_today  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM attendance3 WHERE date='$today'"))[0];
    $rate = $total_students > 0 ? round(($present_today / $total_students) * 100) : 0;
  ?>

  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-label">Total Students</div>
      <div class="stat-value"><?= $total_students ?></div>
      <div class="stat-sub">Enrolled</div>
    </div>
    <div class="stat-card green">
      <div class="stat-label">Present Today</div>
      <div class="stat-value"><?= $present_today ?></div>
      <div class="stat-sub">of <?= $total_students ?> students</div>
    </div>
    <div class="stat-card red">
      <div class="stat-label">Absent Today</div>
      <div class="stat-value"><?= $absent_today ?></div>
      <div class="stat-sub">as of today</div>
    </div>
    <div class="stat-card yellow">
      <div class="stat-label">Attendance Rate</div>
      <div class="stat-value"><?= $rate ?>%</div>
      <div class="stat-sub"><?= $marked_today > 0 ? 'Attendance marked' : 'Not marked yet' ?></div>
    </div>
  </div>

  <!-- Add Student -->
  <div class="card">
    <div class="card-title">Add Student</div>
    <form action="add_student.php" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label>Student Name</label>
          <input type="text" name="name" placeholder="e.g. Arjun Kumar" required>
        </div>
        <div class="form-group">
          <label>Roll Number</label>
          <input type="text" name="roll" placeholder="e.g. 21CS001" required>
        </div>
        <div class="form-group" style="max-width:100px">
          <label>Year</label>
          <input type="number" name="year" placeholder="2" min="1" max="4" required>
        </div>
        <div class="form-group" style="max-width:100px">
          <label>Semester</label>
          <input type="number" name="sem" placeholder="4" min="1" max="8" required>
        </div>
        <div class="form-group" style="display:flex; align-items:flex-end;">
          <button type="submit" class="btn btn-primary" style="width:100%">+ Add</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Mark Attendance -->
  <div class="card">
    <div class="card-title">Mark Attendance — <?= $today ?></div>
    <?php
      $students = mysqli_query($conn, "SELECT * FROM students3 ORDER BY roll_no ASC");
      $count = mysqli_num_rows($students);
    ?>
    <?php if($count === 0): ?>
      <div class="empty-state">
        <div class="icon">◈</div>
        <p>No students yet. Add one above to get started.</p>
      </div>
    <?php else: ?>
      <form method="POST" action="save_attendance.php">
        <div style="overflow-x:auto;">
          <table class="attend-table">
            <thead>
              <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Year/Sem</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($students)): ?>
              <tr>
                <td><span class="roll-badge"><?= htmlspecialchars($row['roll_no']) ?></span></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td style="color:var(--muted); font-family:'DM Mono',monospace; font-size:13px">Y<?= $row['year'] ?>/S<?= $row['semester'] ?></td>
                <td>
                  <div class="radio-group">
                    <label class="radio-label">
                      <input type="radio" name="attendance3[<?= $row['id'] ?>]" value="Present" required>
                      <span class="radio-btn p">P</span>
                    </label>
                    <label class="radio-label">
                      <input type="radio" name="attendance3[<?= $row['id'] ?>]" value="Absent">
                      <span class="radio-btn a">A</span>
                    </label>
                    <label class="radio-label">
                      <input type="radio" name="attendance3[<?= $row['id'] ?>]" value="OD">
                      <span class="radio-btn o">OD</span>
                    </label>
                    <label class="radio-label">
                      <input type="radio" name="attendance3[<?= $row['id'] ?>]" value="Leave">
                      <span class="radio-btn l">LV</span>
                    </label>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <div style="margin-top: 20px;">
          <button type="submit" class="btn btn-success">✓ &nbsp;Save Attendance</button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>