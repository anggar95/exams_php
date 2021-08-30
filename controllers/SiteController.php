<?php

namespace app\controllers;

use Yii;
use app\models\Exam;
use yii\web\Controller;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class SiteController extends Controller
{
	public function actionIndex () {
		$this->enableCsrfValidation = false;
		$d = date('Y-m-d');
		$deadline = Yii::$app->request->get('deadline');		
		$data = new ActiveDataProvider([
			'query' => Exam::find() -> where (['>','deadline',$d]),
			
			'pagination' => [
				'pageSize' => 5,
			],
		]);
		$total = $data->totalCount;
		$count = $data->count;
		
		$result = GridView::widget([
    'dataProvider' => $data,
    'id' => 'grid',
	'summary' => 'Показан <b>{begin}-{end}</b> из <b>{totalCount}</b> экзамен сессии',
    'layout' => '{items}<div class="d-flex justify-content-center">{pager}</div>{summary}',
    'tableOptions' => ['class'=>'grid-table'],
    'columns' => [
        [
            'attribute' => 'id',
            'options'=>['width'=>'40'],
            
        ],
        [
            'attribute'=>'Name',
            'options'=>['width'=>'180'],
        ],
		[
            'attribute'=>'Deadline',
            'format' => ['date', 'php:Y-m-d'],
            'options'=>['width'=>'120'],
            
        ],
		[
		    'attribute'=>'T',
		    'options'=>['width'=>'40'],
		    
		],
    ],
]);
		
		$req = \Yii::$app->request;
		if ($req->post('act')=='addExam')
		{
			$name=$req->post('examName');
			$deadline=$req->post('examDeadline');
			$t=$req->post('examT');
			if ($t!=null && $deadline!=null && $name!=null)
			{
				$exam = new Exam();
				$exam->Name=$name;
				$exam->Deadline=$deadline;
				$exam->T=$t;
				$exam->save();
			}
			else
			{
				echo "<script>alert('null')</script>";
			}
		}	
		
		elseif ($req->post('act')=='setExam')
		{
			$start = [];
			$end = [];
			$d=date('Y-m-d');
			$rows = Exam::find() -> where (['>','Deadline',$d])->select(['Name','Deadline','T'])->orderBy('Deadline')->all();
			foreach($rows as $k=>$v) 
			{
				$arr[] =['Name'=>$v->Name,'Deadline'=>$v->Deadline,'T'=>$v->T];
			}
			
			foreach($arr as $key => $day)
			{
				$d2 = $arr[$key]['T'];
				$dx=date("Y-m-d",strtotime($arr[$key]['Deadline']."-".$d2." day"));
				$dy=date("Y-m-d",strtotime($arr[$key]['Deadline']."-1 day"));
				if ($key>0)
				{
					if ($dx<=$arr[$key-1]['Deadline'])
						$dx=date("Y-m-d",strtotime($arr[$key-1]['Deadline']."+1 day"));
					if ($dx>=$arr[$key]['Deadline'])
					{
						$dx=$end[$key-1];
						$end[$key-1]=date("Y-m-d",strtotime($end[$key-1]."-1 day"));
					}
				}
				$start[] = $dx;
				$end[] = $dy;
			}
			$id = 1;
			$exams = array();
			foreach($arr as $key => $day)
			{
				$prepDay = round((strtotime($end[$key]) - strtotime($start[$key]))/3600/24)+1;
				array_push($exams,
				    ['id' => $id, 'Name' => $arr[$key]['Name'], 'Deadline' => $arr[$key]['Deadline'], 'Prepare' =>$start[$key]." - ".$end[$key], 'PrepareDay' =>$prepDay." day"]
				);
				$id = $id + 1;
			}
			
			//var_dump($exams);
			
			$provider = new ArrayDataProvider([
				'allModels' => $exams,
				    'pagination' => [
				        'pageSize' => 10,
				    ],
				    'sort' => [
				        'attributes' => ['Deadline'],
				    ],
			]);
			
			
			$total = $provider->totalCount;
					$count = $provider->count;
					
					$result = GridView::widget([
			    'dataProvider' => $provider,
				'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
			    'id' => 'grid',
				'summary' => 'Показан <b>{begin}-{end}</b> из <b>{totalCount}</b> экзамен сессии',
			    'layout' => '{items}<div class="d-flex justify-content-center">{pager}</div>{summary}',
			    'tableOptions' => ['class'=>'grid-table'],
			    'columns' => [
			        [
			            'attribute' => 'id',
			            'options'=>['width'=>'40'],
			        ],
			        [
			            'attribute'=>'Name',
			            'options'=>['width'=>'180'],
			        ],
					[
			            'attribute'=>'Deadline',
			            'options'=>['width'=>'120'],
			        ],
					[
					    'attribute'=>'Prepare',
					    'options'=>['width'=>'200'],
					],
					[
					    'attribute'=>'PrepareDay',
					    'options'=>['width'=>'100'],
					],
			    ],
			]);
		}
		return $this->render('index', [
		            'result' => $result,
		        ]);
		
	
	
	}
}
