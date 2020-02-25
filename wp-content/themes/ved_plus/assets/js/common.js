$( document ).ready(function() {
    $('.owl-carousel').owlCarousel({
	    items:1,
	    nav:true,
	    loop:true,
	    dots:true
    });
	//--------------------------------------[ scroll  ]
	$(".scroll").on("click", function (event) {

		event.preventDefault();

		var id  = $(this).attr('href');
		var	top = $(id).offset().top;

		$('body,html').animate({
			scrollTop: top
		}, 1500);

	});

	$('.main_nav .modal_menu').click(function () {

		var nav = $('.main_nav nav');
		if(nav.css('display') === 'none'){

			nav.css({
				'display':'flex'
			});
		}else{
			nav.css({
				'display':'none'
			});
		}
	});

	document.addEventListener( 'wpcf7mailsent', function( event ) {
		if(event.detail.contactFormId=="5"){
			yaCounter51351706.reachGoal('mainFormPayment');
			yaCounter51351706.reachGoal('bestWay');
			console.log('yandex.metrika.compleate');
		}
		if(event.detail.contactFormId=="182"){
			yaCounter51351706.reachGoal('mainFormConsultation');
			console.log('yandex.metrika.compleate');
		}
	}, false );

	$('.main_nav .email').on('copy',function () {
		yaCounter51351706.reachGoal('emailcopy');
		console.log('yandex.metrika.copy');
	});
});