<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AttendOS — Reports</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg:      #0a0a0f;
      --surface: #111118;
      --border:  #1e1e2e;
      --accent:  #7fff6e;
      --accent2: #6edfff;
      --danger:  #ff6e6e;
      --warn:    #ffd76e;
      --text:    #e8e8f0;
      --muted:   #5a5a72;
      --radius:  12px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Syne', sans-serif;
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar same as index */
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
    }
    .sidebar-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; padding-left: 4px; }
    .brand-icon { font-size: 22px; color: var(--accent); filter: drop-shadow(0 0 8px var(--accent)); }
    .brand-text { font-size: 18px; font-weight: 800; letter-spacing: 1px; }
    .nav-links { list-style: none; flex: 1; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 11px 14px; border-radius: var(--radius); color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s; margin-bottom: 4px; }
    .nav-item:hover { background: var(--border); color: var(--text); }
    .nav-item.active { background: rgba(127,255,110,0.08); color: var(--accent); border: 1px solid rgba(127,255,110,0.2); }
    .nav-icon { font-size: 16px; }
    .sidebar-footer { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--muted); padding-left: 4px; }
    .status-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 6px var(--accent); animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    .main {
      margin-left: 220px;
      flex: 1;
      padding: 36px 40px;
      animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    .page-header { margin-bottom: 32px; }
    .page-header h1 { font-size: 28px; font-weight: 800; letter-spacing: -0.5px; }
    .page-header p { color: var(--muted); font-size: 14px; margin-top: 4px; font-family: 'DM Mono', monospace; }

    /* Filter bar */
    .filter-bar {
      display: flex;
      gap: 12px;
      align-items: flex-end;
      margin-bottom: 24px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 20px 24px;
    }

    .filter-bar label {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--muted);
      font-family: 'DM Mono', monospace;
      display: block;
      margin-bottom: 8px;
    }

    select, input[type="date"] {
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px;
      color: var(--text);
      font-family: 'DM Mono', monospace;
      font-size: 13px;
      outline: none;
      transition: border-color 0.2s;
    }

    select:focus, input[type="date"]:focus { border-color: var(--accent); }

    .btn {
      padding: 10px 24px;
      border: none;
      border-radius: 8px;
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .btn-primary { background: var(--accent); color: #0a0a0f; }
    .btn-primary:hover { background: #9fff91; box-shadow: 0 0 20px rgba(127,255,110,0.3); }

    /* Table */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
    }

    .card-title {
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--muted);
      font-family: 'DM Mono', monospace;
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .record-count {
      background: var(--border);
      color: var(--accent2);
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 12px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--muted);
      padding: 12px 24px;
      text-align: left;
      font-family: 'DM Mono', monospace;
      background: rgba(255,255,255,0.01);
    }

    td {
      padding: 14px 24px;
      border-top: 1px solid var(--border);
      font-size: 14px;
    }

    tr:hover td { background: rgba(255,255,255,0.02); }

    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      font-family: 'DM Mono', monospace;
      letter-spacing: 0.5px;
    }

    .badge-present { background: rgba(127,255,110,0.12); color: var(--accent); border: 1px solid rgba(127,255,110,0.25); }
    .badge-absent  { background: rgba(255,110,110,0.12); color: var(--danger); border: 1px solid rgba(255,110,110,0.25); }
    .badge-od      { background: rgba(110,223,255,0.12); color: var(--accent2); border: 1px solid rgba(110,223,255,0.25); }
    .badge-leave   { background: rgba(255,215,110,0.12); color: var(--warn); border: 1px solid rgba(255,215,110,0.25); }

    .roll-badge {
      background: var(--border);
      color: var(--accent2);
      padding: 3px 10px;
      border-radius: 6px;
      font-family: 'DM Mono', monospace;
      font-size: 12px;
    }

    .empty-state {
      text-align: center;
      padding: 60px;
      color: var(--muted);
    }
    .empty-state .icon { font-size: 40px; margin-bottom: 12px; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main">
  <div class="page-header">
    <h1>Attendance Report</h1>
    <p>Filter and view all attendance records</p>
  </div>

  <!-- Filters -->
  <form method="GET">
    <div class="filter-bar">
      <div>
        <label>Student</label>
        <select name="student_id">
          <option value="">All Students</option>
          <?php
            $students = mysqli_query($conn, "SELECT * FROM student3 ORDER BY roll_no");
            while($s = mysqli_fetch_assoc($students)):
          ?>
            <option value="<?= $s['id'] ?>" <?= (isset($_GET['student_id']) && $_GET['student_id'] == $s['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['roll_no'] . ' — ' . $s['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div>
        <label>Status</label>
        <select name="status">
          <option value="">All Status</option>
          <option value="Present" <?= (isset($_GET['status']) && $_GET['status'] === 'Present') ? 'selected' : '' ?>>Present</option>
          <option value="Absent"  <?= (isset($_GET['status']) && $_GET['status'] === 'Absent')  ? 'selected' : '' ?>>Absent</option>
          <option value="OD"      <?= (isset($_GET['status']) && $_GET['status'] === 'OD')      ? 'selected' : '' ?>>OD</option>
          <option value="Leave"   <?= (isset($_GET['status']) && $_GET['status'] === 'Leave')   ? 'selected' : '' ?>>Leave</option>
        </select>
      </div>
      <div>
        <label>From Date</label>
        <input type="date" name="from" value="<?= isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '' ?>">
      </div>
      <div>
        <label>To Date</label>
        <input type="date" name="to" value="<?= isset($_GET['to']) ? htmlspecialchars($_GET['to']) : '' ?>">
      </div>
      <div style="display:flex; align-items:flex-end;">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
    </div>
  </form>

  <?php
    $where = ["1=1"];
    $params = [];
    $types = "";

    if (!empty($_GET['student_id'])) {
      $where[] = "a.student_id = ?";
      $params[] = (int)$_GET['student_id'];
      $types .= "i";
    }
    if (!empty($_GET['status'])) {
      $allowed = ['Present','Absent','OD','Leave'];
      if (in_array($_GET['status'], $allowed)) {
        $where[] = "a.status = ?";
        $params[] = $_GET['status'];
        $types .= "s";
      }
    }
    if (!empty($_GET['from'])) {
      $where[] = "a.date >= ?";
      $params[] = $_GET['from'];
      $types .= "s";
    }
    if (!empty($_GET['to'])) {
      $where[] = "a.date <= ?";
      $params[] = $_GET['to'];
      $types .= "s";
    }

    $sql = "SELECT s.name, s.roll_no, a.date, a.status
            FROM attendances3 a
            JOIN student3 s ON s.id = a.student_id
            WHERE " . implode(" AND ", $where) . "
            ORDER BY a.date DESC, s.roll_no ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($params) {
      mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_num_rows($result);
  ?>

  <div class="card">
    <div class="card-title">
      <span>Records</span>
      <span class="record-count"><?= $total ?> entries</span>
    </div>

    <?php if ($total === 0): ?>
      <div class="empty-state">
        <div class="icon">◈</div>
        <p>No records found. Try adjusting your filters.</p>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Roll No</th>
            <th>Name</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><span class="roll-badge"><?= htmlspecialchars($row['roll_no']) ?></span></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td style="font-family:'DM Mono',monospace; color:var(--muted); font-size:13px"><?= $row['date'] ?></td>
            <td>
              <?php
                $cls = match($row['status']) {
                  'Present' => 'badge-present',
                  'Absent'  => 'badge-absent',
                  'OD'      => 'badge-od',
                  'Leave'   => 'badge-leave',
                  default   => ''
                };
              ?>
              <span class="badge <?= $cls ?>"><?= $row['status'] ?></span>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</div>
</body>
</html>