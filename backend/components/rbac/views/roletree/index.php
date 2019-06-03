<?php
use execut\widget\TreeView;
use yii\web\JsExpression;
use backend\components\rbac\models\AuthItem;
use backend\components\rbac\models\AuthItemChild;


//Bootstrap Tree View widget wrapper for yii2
//https://github.com/execut/yii2-widget-bootstraptreeview
/*	СОБИРАЕМ ДЕРЕВО РОЛЕЙ ВОТ В ТАКОЙ МАССИВ -
Array
(
    [0] => Array
        (
            [text] => administrator
            [color] => blue
            [nodes] => Array
                (
                    [0] => Array
                        (
                            [text] => rbacAdmin
                            [color] => green
                        )

                    [1] => Array
                        (
                            [text] => topmanager
                            [color] => blue
                        )

                    [2] => Array
                        (
                            [text] => userDelete
                            [color] => green
                        )

                )

        )

    [1] => Array
        (
            [text] => exporter
            [color] => blue
            [nodes] => Array
                (
                    [0] => Array
                        (
                            [text] => export
                            [color] => green
                        )

                )

        )

    [2] => Array
        (
            [text] => importer
            [color] => blue
            [nodes] => Array
                (
                    [0] => Array
                        (
                            [text] => import
                            [color] => green
                        )

                )

        )

    [3] => Array
        (
            [text] => opsoser
            [color] => blue
            [nodes] => Array
                (
                    [0] => Array
                        (
                            [text] => logView
                            [color] => green
                        )

                    [1] => Array
                        (
                            [text] => opsos
                            [color] => green
                        )

                )

        )

    [4] => Array
        (
            [text] => topmanager
            [color] => blue
            [nodes] => Array
                (
                    [0] => Array
                        (
                            [text] => exporter
                            [color] => blue
                        )

                    [1] => Array
                        (
                            [text] => importer
                            [color] => blue
                        )

                    [2] => Array
                        (
                            [text] => logView
                            [color] => green
                        )

                    [3] => Array
                        (
                            [text] => opsoser
                            [color] => blue
                        )

                    [4] => Array
                        (
                            [text] => userAdmin
                            [color] => green
                        )

                )

        )

    [5] => Array
        (
            [text] => user
            [color] => blue
            [nodes] => Array
                (
                )

        )

)
*/

foreach($roles as $role) {
	$nodes = array();
	$tmp['text'] = $role->name;
	$tmp['color'] = 'green';

	$childs = AuthItemChild::find()
							->where(['parent' => $role->name])
							->orderBy('child')
							->all();

	foreach($childs as $child) {
		$tmp2['text'] = $child->child;
		$type = AuthItem::find()
								->select('type')
								->where(['name' => $child->child])
								->one();

		$tmp2['color'] = ($type->type==1) ? 'green' : 'blue';		//РОЛИ - зеленым; РАЗРЕШЕНИЯ - синим;
		$nodes[] = $tmp2;
	}
	$tmp['nodes'] = $nodes;
	$data[] = $tmp;
}

//echo"<br><pre>"; print_r($data); echo"</pre>"; 


$onSelect = new JsExpression(<<<JS
function (undefined, item) {
	console.log(item);
}
JS
);
$groupsContent = TreeView::widget([
	'data' => $data,
	'size' => TreeView::SIZE_SMALL,
	'header' => 'Дерево ролей',
	'searchOptions' => [
		'inputOptions' => [
			'placeholder' => 'Найти...'
		],
	],
	'clientOptions' => [
		'onNodeSelected' => $onSelect,
		'selectedBackColor' => 'rgb(40, 153, 57)',
		'borderColor' => '#fff',
	],
]);


echo $groupsContent;
