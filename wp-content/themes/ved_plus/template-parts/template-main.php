<?php
/*
Template Name: Главная
*/


$context = Timber::get_context();
$context['options'] = get_fields('options');
foreach ($context['options']['cargo'] as $key=> $item){
	$context['options']['cargo'][$item['title']]=$item['img'];
	unset($context['options']['cargo'][$key]);
}

foreach ($context['options']['cargotype_items'] as$keyV=> $value){
	foreach ($value['type'] as $key=>$item){
		$title='';

		switch ($context['options']['cargotype_items'][$keyV]['type'][$key]){
			case 'aircraft':
				$title='Самолетом';
				break;
			case 'car':
				$title='Автомобилем';
				break;
			case 'train':
				$title='Железной дорогой';
				break;
			case 'sea':
				$title='Морем';
				break;
		}

		$context['options']['cargotype_items'][$keyV]['type'][$key]=[];
		$context['options']['cargotype_items'][$keyV]['type'][$key]['url'] = 'fsdfsd';
		$context['options']['cargotype_items'][$keyV]['type'][$key]['alt'] = '';
		$context['options']['cargotype_items'][$keyV]['type'][$key]['url'] = $context['options']['cargo'][$item]['url'];
		$context['options']['cargotype_items'][$keyV]['type'][$key]['alt'] = $context['options']['cargo'][$item]['alt'];
		$context['options']['cargotype_items'][$keyV]['type'][$key]['title'] = $title;
	}
}
foreach ($context['options']['step_items'] as $key=>$item ){
	$step=$key+1;
	switch ($step){
		case 1:
			$step='&#8544;';
			break;
		case 2:
			$step='&#8545;';
			break;
		case 3:
			$step='&#8546;';
			break;
	}
	$context['options']['step_items'][$key]['step']='';
	$context['options']['step_items'][$key]['step']=$step;
}


Timber::render('header.twig',$context);
Timber::render('title.twig',$context);
Timber::render('country.twig',$context);
Timber::render('select.twig',$context);
Timber::render('cargo_type.twig',$context);
Timber::render('five_block.twig',$context);
Timber::render('step.twig',$context);
Timber::render('form1.twig',$context);
Timber::render('carousel.twig',$context);

Timber::render('team.twig',
	[
		'title'=>get_field('team_title','options'),
		'items'=>get_field('team','options'),
	]);

Timber::render('form1.twig',$context);
Timber::render('footer.twig',$context);
get_footer();

?>
