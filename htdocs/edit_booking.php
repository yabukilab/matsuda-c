<?php
// File: edit_booking.php

// デバッグ表示（不要ならコメントアウト）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始＆DB接続
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// ログイン＆IDチェック
if (empty($_SESSION['Login_number']) || !isset($_GET['id'])) {
    header('Location: booking.php');
    exit;
}

$ln = (int)$_SESSION['Login_number'];
$id = (int)$_GET['id'];  // 修正：$を追加

// 予約データ取得
$stmt = $db->prepare(
    'SELECT teacher_id, lesson_type_id, Lesson_date, Lesson_time
     FROM comments
     WHERE id = :id AND Login_number = :ln'
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$res) {
    header('Location: booking.php');
    exit;
}

// 講師一覧取得
$teachers = $db->query(
    'SELECT teacher_id, teacher_name FROM mst_teachers ORDER BY teacher_name'
)->fetchAll(PDO::FETCH_ASSOC);

// 種別一覧取得
$types = $db->query(
    'SELECT lesson_type_id, lesson_type_name FROM mst_lesson_types ORDER BY lesson_type_id'
)->fetchAll(PDO::FETCH_ASSOC);

// 自身の予約済み日付一覧取得（編集対象以外）
$bookedStmt = $db->prepare(
    'SELECT DISTINCT Lesson_date FROM comments WHERE Login_number = :ln AND id != :id'
);
$bookedStmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$bookedStmt->bindValue(':id', $id, PDO::PARAM_INT);
$bookedStmt->execute();
$bookedDates = $bookedStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>予約編集</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
    <div style="display:flex; align-items:center; position: relative;">
  <div class="menu-btn" id="menuBtn">
    <span></span>
    <span></span>
    <span></span>
  </div>
  <div class="dropdown-menu" id="dropdownMenu">
    <a href="booking.php">Home</a>
    <a href="new_booking.php">新規予約</a>
    <a href="logout.php">Logout</a>
  </div>
  <!-- 既存の <h2> やタイトルが続きます -->
</div>

      <h2>🎹 ピアノレッスン予約システム</h2>
    </div>
    <div class="card">
      <h3>🗓️ 予約編集</h3>
      <div class="calendar-header">
        <button id="prevMonth" class="calendar-nav">◀</button>
        <span id="currentMonth" class="calendar-month"></span>
        <button id="nextMonth" class="calendar-nav">▶</button>
      </div>
      <div id="calendar" class="calendar"></div>
      <form id="bookingForm" method="post" action="update_booking.php" style="display:none; margin-top:20px;">
        <input type="hidden" name="id" value="<?= h($id) ?>">
        <label for="teacherSelect"><br>講師を選択</label>
        <select name="teacher_id" id="teacherSelect" required>
          <option value="" disabled>-- 講師を選択 --</option>
          <?php foreach ($teachers as $t): ?>
            <option value="<?= h($t['teacher_id']) ?>" <?= $t['teacher_id']==$res['teacher_id']?'selected':'' ?>><?= h($t['teacher_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <label for="lessonTypeSelect"><br>種別を選択</label>
        <select name="lesson_type_id" id="lessonTypeSelect" required>
          <option value="" disabled>-- 種別を選択 --</option>
          <?php foreach ($types as $ty): ?>
            <option value="<?= h($ty['lesson_type_id']) ?>" <?= $ty['lesson_type_id']==$res['lesson_type_id']?'selected':'' ?>><?= h($ty['lesson_type_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="Lesson_date" id="selectedDate" value="<?= h($res['Lesson_date']) ?>">
        <label for="TimeTypeSelect"><br>時間を選択</label>
        <div id="timeSlots" class="time-slots"></div>
        <a href="booking_detail.php" class="btn btn-small">🏠 Home</a>
        <button type="submit" class="btn btn-small" style="margin-top:10px;">更新確定</button> 

      </form>
    </div>
  </div>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
    const calendar = document.getElementById('calendar');
    const monthEl = document.getElementById('currentMonth');
    const form = document.getElementById('bookingForm');
    const selectedDateInput = document.getElementById('selectedDate');
    const timeSlotsEl = document.getElementById('timeSlots');
    const slots = ['09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00'];
    const bookedDates = <?= json_encode($bookedDates, JSON_UNESCAPED_UNICODE) ?>;
    const initialDate = new Date('<?= $res['Lesson_date'] ?>');
    const initialTime = '<?= $res['Lesson_time'] ?>';

    function renderCalendar() {
      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();
      monthEl.textContent = `${year}年${month+1}月`;
      const maxDate = new Date(today);
      maxDate.setMonth(maxDate.getMonth()+3);
      maxDate.setDate(1);
      const firstDay = new Date(year, month, 1);
      const start = new Date(firstDay);
      start.setDate(start.getDate()-start.getDay());
      calendar.innerHTML = '';
      ['日','月','火','水','木','金','土'].forEach(d => {
        const hdr = document.createElement('div'); hdr.className='calendar-day weekday-name'; hdr.textContent=d; calendar.appendChild(hdr);
      });
      for (let i=0;i<42;i++){
        const d=new Date(start); d.setDate(start.getDate()+i);
        const cell=document.createElement('div'); cell.className='calendar-day';
        const dateStr=`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        if(d.getMonth()!==month||d<today||d>=maxDate){ cell.classList.add('other-month'); }
        else if(bookedDates.includes(dateStr)){ cell.classList.add('user-booked'); cell.textContent='予約済'; }
        else{
          cell.classList.add('available'); cell.textContent=d.getDate(); if(d.toDateString()===initialDate.toDateString()){ cell.classList.add('selected'); } cell.addEventListener('click',()=>selectDate(d));
        }
        calendar.appendChild(cell);
      }
    }
    function selectDate(date){
      document.querySelectorAll('.calendar-day.selected').forEach(el=>el.classList.remove('selected'));
      const y=date.getFullYear(), m=String(date.getMonth()+1).padStart(2,'0'), d=String(date.getDate()).padStart(2,'0');
      selectedDateInput.value=`${y}-${m}-${d}`;
      document.querySelectorAll('.calendar-day.available').forEach(el=>{ if(el.textContent==date.getDate()){ el.classList.add('selected'); }});
      renderTimeSlots(); form.style.display='block'; form.scrollIntoView({behavior:'smooth'});
    }
    function renderTimeSlots(){ timeSlotsEl.innerHTML=''; slots.forEach(time=>{ const btn=document.createElement('div'); btn.className='time-slot'; btn.textContent=time; if(time===initialTime){ btn.classList.add('selected'); } btn.addEventListener('click',()=>{ document.querySelectorAll('.time-slot.selected').forEach(el=>el.classList.remove('selected')); btn.classList.add('selected'); let tInput=document.getElementById('selectedTime'); if(!tInput){ tInput=document.createElement('input'); tInput.type='hidden'; tInput.name='Lesson_time'; tInput.id='selectedTime'; form.appendChild(tInput);} tInput.value=time; }); timeSlotsEl.appendChild(btn);} ); }
    document.getElementById('prevMonth').addEventListener('click',()=>{currentDate.setMonth(currentDate.getMonth()-1); renderCalendar();});
    document.getElementById('nextMonth').addEventListener('click',()=>{currentDate.setMonth(currentDate.getMonth()+1); renderCalendar();});
    renderCalendar();
    renderTimeSlots(); form.style.display='block';
  });
  </script>
  <script>
// メニューの表示／非表示
document.getElementById('menuBtn').addEventListener('click', function(e) {
  const menu = document.getElementById('dropdownMenu');
  menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  e.stopPropagation();
});
// メニュー外クリックで閉じる
document.addEventListener('click', function() {
  document.getElementById('dropdownMenu').style.display = 'none';
});
</script>

  </body>