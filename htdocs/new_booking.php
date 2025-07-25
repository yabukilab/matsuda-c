<?php
// File: new_booking.php

// セッション開始＆DB接続
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// ログインチェック
if (empty($_SESSION['Login_number'])) {
    header('Location: index.php');
    exit;
}
$ln = (int)$_SESSION['Login_number'];

// ── 全ユーザー予約データ取得 ──
$allBooked = $db->query(
    'SELECT Lesson_date, teacher_id, Lesson_time FROM comments'
)->fetchAll(PDO::FETCH_ASSOC);

// ── 講師一覧 ──
$teachers = $db->query(
    'SELECT teacher_id, teacher_name FROM mst_teachers ORDER BY teacher_name'
)->fetchAll(PDO::FETCH_ASSOC);
// ── 種別一覧 ──
$types = $db->query(
    'SELECT lesson_type_id, lesson_type_name FROM mst_lesson_types ORDER BY lesson_type_id'
)->fetchAll(PDO::FETCH_ASSOC);
// ── 自身の予約日一覧 ──
$userDatesStmt = $db->prepare(
    'SELECT DISTINCT Lesson_date FROM comments WHERE Login_number = :ln'
);
$userDatesStmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$userDatesStmt->execute();
$userBookedDates = $userDatesStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>新規予約</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>新規予約</h1>
      <a href="booking.php" class="btn btn-secondary">Home</a>
    </div>
    <div class="card">
      <h2>新規予約</h2>
      <div class="calendar-header">
        <button id="prevMonth" class="calendar-nav">◀</button>
        <span id="currentMonth" class="calendar-month"></span>
        <button id="nextMonth" class="calendar-nav">▶</button>
      </div>
      <div id="calendar" class="calendar"></div>

      <form id="bookingForm" method="post" action="save_booking.php" style="margin-top:20px;">
        <label for="teacherSelect">講師を選択</label>
        <select name="teacher_id" id="teacherSelect" required>
          <option value="" disabled selected>-- 講師を選択 --</option>
          <?php foreach ($teachers as $t): ?>
            <option value="<?= h($t['teacher_id']) ?>"><?= h($t['teacher_name']) ?></option>
          <?php endforeach; ?>
        </select>

        <label for="lessonTypeSelect">種別を選択</label>
        <select name="lesson_type_id" id="lessonTypeSelect" required>
          <option value="" disabled selected>-- 種別を選択 --</option>
          <?php foreach ($types as $ty): ?>
            <option value="<?= h($ty['lesson_type_id']) ?>"><?= h($ty['lesson_type_name']) ?></option>
          <?php endforeach; ?>
        </select>

        <input type="hidden" name="Lesson_date" id="selectedDate">
        <h4>時間を選択</h4>
        <div id="timeSlots" class="time-slots"></div>
        <button type="submit" class="btn" style="margin-top:10px;">予約確定</button>
      </form>
    </div>
  </div>

  <script>
    // ── 全予約マップ ──
    const bookedArr = <?= json_encode($allBooked, JSON_UNESCAPED_UNICODE) ?>;
    const bookedMap = {};
    bookedArr.forEach(({Lesson_date, teacher_id, Lesson_time}) => {
      const key = `${Lesson_date}__${teacher_id}`;
      bookedMap[key] = bookedMap[key] || [];
      bookedMap[key].push(Lesson_time.slice(0,5));
    });
    // ── ユーザー自身の予約日 ──
    const userBookedDates = <?= json_encode($userBookedDates, JSON_UNESCAPED_UNICODE) ?>;

    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date();
      let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
      const calendar       = document.getElementById('calendar');
      const monthEl        = document.getElementById('currentMonth');
      const form           = document.getElementById('bookingForm');
      const selectedDateEl = document.getElementById('selectedDate');
      const teacherSelect  = document.getElementById('teacherSelect');
      const timeSlotsEl    = document.getElementById('timeSlots');
      const slots          = ['09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00'];

      function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        monthEl.textContent = `${year}年${month+1}月`;
        const first = new Date(year, month, 1);
        const start = new Date(first);
        start.setDate(start.getDate() - start.getDay());
        calendar.innerHTML = '';
        ['日','月','火','水','木','金','土'].forEach(d => {
          const hd = document.createElement('div');
          hd.className = 'calendar-day weekday-name';
          hd.textContent = d;
          calendar.appendChild(hd);
        });
        for (let i = 0; i < 42; i++) {
          const d = new Date(start);
          d.setDate(start.getDate() + i);
          const cell = document.createElement('div');
          cell.className = 'calendar-day';
          const dateStr = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
          if (d.getMonth() !== month || d < today) {
            cell.classList.add('other-month');
          } else if (userBookedDates.includes(dateStr)) {
            cell.classList.add('user-booked');
            cell.textContent = '予約済';
          } else {
            cell.classList.add('available');
            cell.textContent = d.getDate();
            cell.addEventListener('click', () => selectDate(d));
          }
          calendar.appendChild(cell);
        }
      }

      function selectDate(date) {
        document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
        const y = date.getFullYear(), m = String(date.getMonth()+1).padStart(2,'0'), d = String(date.getDate()).padStart(2,'0');
        selectedDateEl.value = `${y}-${m}-${d}`;
        document.querySelectorAll('.calendar-day.available')
          .forEach(el => { if (el.textContent == date.getDate()) el.classList.add('selected'); });
        renderTimeSlots();
        form.style.display = 'block';
      }

      function renderTimeSlots() {
        timeSlotsEl.innerHTML = '';
        const selDate = selectedDateEl.value;
        const selTch  = teacherSelect.value;
        const key     = `${selDate}__${selTch}`;
        const disabled = bookedMap[key] || [];
        slots.forEach(time => {
          const btn = document.createElement('div');
          btn.className = 'time-slot';
          btn.textContent = time;
          if (disabled.includes(time)) {
            btn.classList.add('booked');
          } else {
            btn.addEventListener('click', () => {
              document.querySelectorAll('.time-slot.selected')
                .forEach(el => el.classList.remove('selected'));
              btn.classList.add('selected');
              let inp = document.getElementById('selectedTime');
              if (!inp) {
                inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = 'Lesson_time'; inp.id = 'selectedTime';
                form.appendChild(inp);
              }
              inp.value = time;
            });
          }
          timeSlotsEl.appendChild(btn);
        });
      }

      // teacherSelect変更で再レンダリング
      teacherSelect.addEventListener('change', renderTimeSlots);

      document.getElementById('prevMonth').addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth()-1); renderCalendar(); });
      document.getElementById('nextMonth').addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth()+1); renderCalendar(); });

      renderCalendar();
      renderTimeSlots();
    });
  </script>