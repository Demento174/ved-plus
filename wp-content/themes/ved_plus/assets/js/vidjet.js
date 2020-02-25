$( document ).ready(function() {

	var counter =51351706;//счётчик метрики

	var phoneNumber='88007070334'; //Номер телефона (обычный звонок)
	var phoneGoal='call'; //Цель метрики (обычный звонок)

	var watsAppNumber='+79517788153';//Номер телефона для WatsApp
	var watsAppeGoal='whasapp'; //Цель метрики (watsApp)

	var viberNumber='+79517788153';//Номер телефона для viber
	var viberGoal='viber'; //Цель метрики (viber)

	var dataObject=[
		{
			'phone':{
				'href':'tel:'+phoneNumber,
				'reachGoal':phoneGoal,
				'svg':{
					'width':'26px',
					'height':'41px',
					'data-prefix':'fas',
					'data-icon':'mobile-alt',
					'class':'svg-inline--fa fa-mobile-alt fa-w-10',
					'viewBox':'0 0 320 512',
					'd':'M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm112-108c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z',
				},
				'text':'Позвонить'
			},

			'watsApp':{
				'href':'whatsapp://send?phone='+watsAppNumber+'&amp;abid='+watsAppNumber,
				'reachGoal':watsAppeGoal,
				'svg':{
					'width':'41px',
					'height':'41px',
					'data-prefix':'fab',
					'data-icon':'whatsapp',
					'class':'svg-inline--fa fa-whatsapp fa-w-14',
					'viewBox':'0 0 448 512',
					'd':'M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z',
				},
				'text':'Чат WhatsApp'
			},

			'viber':{

				'href':'viber://chat?number='+viberNumber,
				'reachGoal':viberGoal,
				'svg':{
					'width':'39px',
					'height':'41px',
					'data-prefix':'fab',
					'data-icon':'viber',
					'class':'svg-inline--fa fa-viber fa-w-16',
					'viewBox':'0 0 512 512',
					'd':'M444 49.9C431.3 38.2 379.9.9 265.3.4c0 0-135.1-8.1-200.9 52.3C27.8 89.3 14.9 143 13.5 209.5c-1.4 66.5-3.1 191.1 117 224.9h.1l-.1 51.6s-.8 20.9 13 25.1c16.6 5.2 26.4-10.7 42.3-27.8 8.7-9.4 20.7-23.2 29.8-33.7 82.2 6.9 145.3-8.9 152.5-11.2 16.6-5.4 110.5-17.4 125.7-142 15.8-128.6-7.6-209.8-49.8-246.5zM457.9 287c-12.9 104-89 110.6-103 115.1-6 1.9-61.5 15.7-131.2 11.2 0 0-52 62.7-68.2 79-5.3 5.3-11.1 4.8-11-5.7 0-6.9.4-85.7.4-85.7-.1 0-.1 0 0 0-101.8-28.2-95.8-134.3-94.7-189.8 1.1-55.5 11.6-101 42.6-131.6 55.7-50.5 170.4-43 170.4-43 96.9.4 143.3 29.6 154.1 39.4 35.7 30.6 53.9 103.8 40.6 211.1zm-139-80.8c.4 8.6-12.5 9.2-12.9.6-1.1-22-11.4-32.7-32.6-33.9-8.6-.5-7.8-13.4.7-12.9 27.9 1.5 43.4 17.5 44.8 46.2zm20.3 11.3c1-42.4-25.5-75.6-75.8-79.3-8.5-.6-7.6-13.5.9-12.9 58 4.2 88.9 44.1 87.8 92.5-.1 8.6-13.1 8.2-12.9-.3zm47 13.4c.1 8.6-12.9 8.7-12.9.1-.6-81.5-54.9-125.9-120.8-126.4-8.5-.1-8.5-12.9 0-12.9 73.7.5 133 51.4 133.7 139.2zM374.9 329v.2c-10.8 19-31 40-51.8 33.3l-.2-.3c-21.1-5.9-70.8-31.5-102.2-56.5-16.2-12.8-31-27.9-42.4-42.4-10.3-12.9-20.7-28.2-30.8-46.6-21.3-38.5-26-55.7-26-55.7-6.7-20.8 14.2-41 33.3-51.8h.2c9.2-4.8 18-3.2 23.9 3.9 0 0 12.4 14.8 17.7 22.1 5 6.8 11.7 17.7 15.2 23.8 6.1 10.9 2.3 22-3.7 26.6l-12 9.6c-6.1 4.9-5.3 14-5.3 14s17.8 67.3 84.3 84.3c0 0 9.1.8 14-5.3l9.6-12c4.6-6 15.7-9.8 26.6-3.7 14.7 8.3 33.4 21.2 45.8 32.9 7 5.7 8.6 14.4 3.8 23.6z',
				},
				'text':'Чат Viber'
			}
		}
	];





	if($(window).width()<=800 ){
		setTimeout(function(){
			$('#trade-lightwidget-2169cd1510126889707d').css({
				'bottom':'95px'
			});
		}, 1000);
		createElement(dataObject);
	}else{
		$(".volt-bottom-block").remove();
	}
});//document.ready
function createElement(data){
	var k=0;

	$('<div>', {
		class: "volt-bottom-block",
		css: {
			backgroundColor: '#6a7179',
			display: 'flex',
			justifyContent: 'space-around',
			position: 'fixed',
			bottom: 0,
			left: 0,
			width: '100%'
		}}).appendTo(document.body);

	$.each(data, function(key, array) {
		$.each(array,function(i,item){
			k++;
			var href=$('<a>', {
				href:item['href'],
				class: "volt-href",
				css: {
					paddingTop: '10px',
					paddingBottom: '5px',
					display: 'flex',
					flexDirection: 'column',
					justifyContent: 'space-between',
					alignItems: 'center',
					//margin: '0 10px'
				}});

			var voltImg=$('<span>', {
				class: "volt-img volt-img-"+k,
				css: {
					backgroundColor: '#ffffff',
					display: 'flex',
					alignItems: 'center',
					justifyContent: 'center',
					borderRadius: '5px',
					color: '#0062cc',
					height: '49px',
					width: '18vw',
					maxWidth: '41px',
					backgroundColor:'#6a7179'
				},
				click: function() {


					yaCounter33192788.reachGoal(item['reachGoal']);
				}
			});

			var voltText=$('<span>', {
				class: "volt-text",
				text:item['text'],
				css: {
					color: '#ffffff',
					fontSize: '16px',
					textAlign: 'center',
					letterSpacing: '0.06rem',
					marginTop: '4px',
					fontFamily: '"Pragmatica Light", Helvetica,Arial,sans-serif'
				}});

			voltImg.appendTo(href);

			voltText.appendTo(href);

			href.appendTo($('.volt-bottom-block'));

			var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
			svg.style.width=item['svg']['width'];
			svg.style.height=item['svg']['height'];
			svg.classList.add('volt-svg-'+k);
			svg.setAttribute("viewBox",item['svg']['viewBox']);
			svg.setAttribute("aria-hidden","true");
			svg.setAttribute("focusable","false");
			svg.setAttribute("data-prefix","fas");
			svg.setAttribute("data-icon","mobile-alt");
			svg.setAttribute("role","img");
			svg.setAttribute("xmlns","http://www.w3.org/2000/svg");

			var voltImgJs=document.getElementsByClassName('volt-img-'+k)[0];
			voltImgJs.appendChild(svg);

			var svgPath = document.createElementNS("http://www.w3.org/2000/svg", 'path');
			svgPath.setAttribute("d",item['svg']['d']);
			svgPath.setAttribute("fill","#ffffff");

			svg.appendChild(svgPath);


		})
	});



	$('a.volt-href:hover').css({
		'textDecoration':'none'
	});

	$('a.volt-href').css({
		'textDecoration':'none'
	});

	$('.volt-bottom-block a:nth-child(2)').css({
		'border-right':'1px solid #ffffff',
		'border-left':'1px solid #ffffff',
		'padding':'10px 0 5px'
	});
	$('.volt-bottom-block a .volt-text').css({
		'font-size':'12px'
	})
	$('.volt-href').css({
		'width':'33%'

	});

	var height=$('.volt-bottom-block').height();

	$('body').css({'margin-bottom':height});



}

