<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>
<div class="site-index">
	<div align="center">
	<h1>Сессия</h1>
	<form method="post" name="examForm">
		<input type="hidden" name="act">
		<div class="form-group">
			<label>Название экзамена</label>
			<input type="text" class="form-control" name="examName">
		</div>
		<div class="form-group">
			<label>Дата экзамена</label>
			<input type="date" class="form-control" name="examDeadline" value="<?php echo date('Y-m-d'); ?>">
		</div>
		<div class="form-group">
			<label>Нужно подготовить позже чем ... день</label>
			<input type="number" class="form-control" name="examT">
		</div>
		<button type="submit" class="btn btn-primary" onclick="document.forms['examForm'].act.value='addExam'">Добавить</button>
		<button type="submit" class="btn btn-primary" onclick="document.forms['examForm'].act.value='setExam'">Рассчитать</button>
	</form>
	<p class="lead">Расписание экзамена</p>
	<p><?= $result ?></p>
	</div>
</div>