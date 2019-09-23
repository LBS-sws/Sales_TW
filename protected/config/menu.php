<?php

return array(

	'Data Entry'=>array(
		'access'=>'HK',
		'icon'=>'fa-edit',
		'items'=>array(
			'Sales Visit'=>array(
				'access'=>'HK01',
				'url'=>'/visit/index',
			),
/*
			'Sales orders'=>array(
				'access'=>'HK02',
				'url'=>'/Salesorder/index',
			),
*/
			'Five Steps'=>array(
			'access'=>'HK03',
				'url'=>'/fivestep/index',
			),
            'Performance Objectives'=>array(
                'access'=>'HK04',
                'url'=>'/performance/index',
            ),
		),
	),

    'Class'=>array(
        'access'=>'HA',
		'icon'=>'fa-database',
        'items'=>array(
            'Visit Steps'=>array(
                'access'=>'HA01',
                'url'=>'report/visit',
            ),
            'Sale Staff'=>array(
                'access'=>'HA02',
                'url'=>'report/staff',
            ),
            'Sales transfer'=>array(
                'access'=>'HA03',
                    'url'=>'/shift/index',
            ),
            'Sales lead'=>array(
                'access'=>'HA04',
                'url'=>'/report/performance',
            ),
            'Sales ranking list'=>array(
                'access'=>'HA05',
                'url'=>'/rankinglist/index',
            ),
        ),
    ),

	'Report'=>array(
		'access'=>'HB',
		'icon'=>'fa-file-text-o',
		'items'=>array(
            'Five Steps'=>array(
                'access'=>'HB02',
                'url'=>'report/five',
            ),
			'Report Manager'=>array(
				'access'=>'HB01',
				'url'=>'/queue/index',
			),
		),
	),
	
	'System Setting'=>array(
		'access'=>'HC',
		'icon'=>'fa-gear',
		'items'=>array(
			'Visit Type'=>array(
				'access'=>'HC01',
				'url'=>'/visittype/index',
				'tag'=>'@',
			),
			'Visit Objective'=>array(
				'access'=>'HC02',
				'url'=>'/visitobj/index',
				'tag'=>'@',
			),
			'Customer Type'=>array(
				'access'=>'HC03',
				'url'=>'/custtype/index',
				'tag'=>'@',
			),
			'Customer District'=>array(
				'access'=>'HC04',
				'url'=>'/district/index',
				'tag'=>'@',
			),
		),
	),

);
