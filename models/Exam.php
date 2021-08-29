<?php
namespace app\models;

use yii\db\ActiveRecord;

class Exam extends ActiveRecord {
	public $name;
	public $deadline;
	public $t;
	
	public function rules()
	{
		return [[['Name'], 'required'],
		[['Deadline'], 'required'],
		 [['T'], 'required']];
		
		
	}
}

?>