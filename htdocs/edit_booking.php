<?php
// File: edit_booking.php
// セッション開始＆DB接続
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// ログイン＆IDチェック
if (empty($_SESSION['Login_number']) || !isset($_GET['id'])) {
    header('Location: booking.php'); exit;
}
$ln = (int)$_SESSION['Login_number'];
$id = (int)$_GET['id'];

// 全予約組み合わせ取得
$allBooked = $db->query(
    'SELECT Lesson_date, teacher_id, Lesson_time FROM comments'
)->fetchAll(PDO::FETCH_ASSOC);

// 編集対象の予約情報取得
$stmt = $db->prepare(
    'SELECT teacher_id, lesson_type_id, Lesson_date, Lesson_time
     FROM comments
     WHERE id = :id AND Login_number = :ln'
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$res) { header('Location: booking.php'); exit; }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>予約編集</title>
  <link rel="stylesheet" href="style.css">
  <style>/* カレンダー／フォームCSS外部化済み */</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>予約編集</h1>
      <a href="booking.php" class="btn btn-secondary">Home</a>
    </div>
    <div class="card">
      <h2>予約編集</h2>
      <div class="calendar-header">
        <button id="prevMonth" class="calendar-nav">◀</button>
        <span id="currentMonth" class="calendar-month"></span>
        <button id="nextMonth" class="calendar-nav">▶</button>
      </div>
      <div id="calendar" class="calendar"></div>

      <form id="bookingForm" method="post" action="update_booking.php" style="margin-top:20px;">
        <input type="hidden" name="id" value="<?= h($id) ?>">
        <?php // 講師プルダウン ?>
        <label for="teacherSelect">講師を選択</label>
        <select name="teacher_id" id="teacherSelect" required>
          <option value="" disabled>-- 講師を選択 --</option>
          <?php foreach ($db->query('SELECT teacher_id, teacher_name FROM mst_teachers ORDER BY teacher_name')->fetchAll(PDO::FETCH_ASSOC) as $t): ?>
            <option value="<?= h($t['teacher_id']) ?>" <?= $t['teacher_id']==$res['teacher_id']?'selected':'' ?> ><?= h($t['teacher_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?php // 種別プルダウン ?>
        <label for="lessonTypeSelect">種別を選択</label>
        <select name="lesson_type_id" id="lessonTypeSelect" required>
          <option value="" disabled>-- 種別を選択 --</option>
          <?php foreach ($db->query('SELECT lesson_type_id, lesson_type_name FROM mst_lesson_types ORDER BY lesson_type_id')->fetchAll(PDO::FETCH_ASSOC) as $ty): ?>
            <option value="<?= h($ty['lesson_type_id']) ?>" <?= $ty['lesson_type_id']==$res['lesson_type_id']?'selected':'' ?> ><?= h($ty['lesson_type_name']) ?></option>
          <?php endforeach; ?>
        </select>

        <input type="hidden" name="Lesson_date" id="selectedDate" value="<?= h($res['Lesson_date']) ?>">
        <h4>時間を選択</h4>
        <div id="timeSlots" class="time-slots"></div>
        <button type="submit" class="btn" style="margin-top:10px;">更新確定</button>
      </form>
    </div>
  </div>

  <script>
    // 全予約マップ作成
    const bookedArr = <?= json_encode($allBooked, JSON_UNESCAPED_UNICODE) ?>;
    const bookedMap = {};
    bookedArr.forEach(({Lesson_date, teacher_id, Lesson_time}) => {
      const key = `${Lesson_date}__${teacher_id}`;
      bookedMap[key] = bookedMap[key] || [];
      bookedMap[key].push(Lesson_time.slice(0,5));
    });

    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date();
      let currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
      const calendar = document.getElementById('calendar');
      const monthEl = document.getElementById('currentMonth');
      const form = document.getElementById('bookingForm');
      const selectedDateInput = document.getElementById('selectedDate');
      const teacherSelect = document.getElementById('teacherSelect');
      const timeSlotsEl = document.getElementById('timeSlots');
      const slots = ['09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00'];
      const initialDate = new Date('<?= $res['Lesson_date'] ?>');
      const initialTime = '<?= substr($res['Lesson_time'],0,5) ?>';

      function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        monthEl.textContent = `${year}年${month+1}月`;
        const firstDay = new Date(year, month, 1);
        const start = new Date(firstDay);
        start.setDate(start.getDate() - start.getDay());
        calendar.innerHTML = '';
        ['日','月','火','水','木','金','土'].forEach(d => {
          const hdr = document.createElement('div'); hdr.className='calendar-day weekday-name'; hdr.textContent=d; calendar.appendChild(hdr);
        });
        for (let i=0;i<42;i++){
          const d = new Date(start);
          d.setDate(start.getDate()+i);
          const cell = document.createElement('div'); cell.className='calendar-day';
          const dateStr = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
          if (d.getMonth()!==month || d<today) {
            cell.classList.add('other-month');
          } else {
            cell.classList.add('available');
            cell.textContent = d.getDate();
            if (d.toDateString()===initialDate.toDateString()) cell.classList.add('selected');
            cell.addEventListener('click',()=>selectDate(d));
          }
          calendar.appendChild(cell);
        }
      }

      function selectDate(date) {
        document.querySelectorAll('.calendar-day.selected').forEach(el=>el.classList.remove('selected'));
        const y=date.getFullYear(),m=String(date.getMonth()+1).padStart(2,'0'),d=String(date.getDate()).padStart(2,'0');
        selectedDateInput.value=`${y}-${m}-${d}`;
        document.querySelectorAll('.calendar-day.available').forEach(el=>{ if(el.textContent==date.getDate()) el.classList.add('selected'); });
        renderTimeSlots(); form.style.display='block';
      }

      function renderTimeSlots() {
        timeSlotsEl.innerHTML = '';
        const selDate = selectedDateInput.value;
        const selTeacher = teacherSelect.value;
        const key = `${selDate}__${selTeacher}`;
        const disabled = bookedMap[key] || [];
        slots.forEach(time => {
          const btn=document.createElement('div'); btn.className='time-slot'; btn.textContent = time;
          if (disabled.includes(time)) {
            btn.classList.add('booked');
          } else {
            btn.addEventListener('click',()=>{
              document.querySelectorAll('.time-slot.selected').forEach(el=>el.classList.remove('selected'));
              btn.classList.add('selected');
              let inp=document.getElementById('selectedTime');
              if(!inp){ inp=document.createElement('input'); inp.type='hidden'; inp.name='Lesson_time'; inp.id='selectedTime'; form.appendChild(inp); }
              inp.value = time;
            });
          }
          timeSlotsEl.appendChild(btn);
        });
      }

      document.getElementById('prevMonth').addEventListener('click',()=>{currentDate.setMonth(currentDate.getMonth()-1);renderCalendar();});
      document.getElementById('nextMonth').addEventListener('click',()=>{currentDate.setMonth(currentDate.getMonth()+1);renderCalendar();});
      renderCalendar(); renderTimeSlots();
    });
  </script>