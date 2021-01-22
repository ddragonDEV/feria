const duration = 250,
	largoinicial = 27,
	loader = PIXI.Loader.shared,
	sprites = {},
	cursor = $('#cursor-a'),
	cursorb = $('#cursor-b'),
	cursorSpeed = .5,
	mapaRatio = .7;

var canvasCreated = false,
	app,
	viewport,
	vid1Controller,
	mouseMoving = false,
	activeMarkerOffset = 0,
	headerHeight = parseInt($('#main_header').outerHeight()),
	footerHeight = parseInt($('#main_footer').outerHeight()),
	sliderz = false;
if (window.innerWidth > window.innerHeight) {
	var mapaWidth = window.innerWidth * 2,
		mapaHeight = mapaWidth * mapaRatio,
		mapaStartZoom = mapaWidth * .2;
} else {
	var mapaWidth = (((window.innerHeight - (headerHeight + footerHeight)) * 2) / (mapaRatio * 10)) * 10,
		mapaHeight = (window.innerHeight - (headerHeight + footerHeight)) * 2,
		mapaStartZoom = mapaWidth * .15;
}

let cursorX = 0,
	cursorY = 0,
	distX = 0,
	distY = 0,
	mouseX = 0,
	mouseY = 0;

function rand(n) {
	return Math.round(Math.random() * n)
}

function checkScroll(val) {
	if ($('body').hasClass('has--popup-carreras') || $('body').hasClass('has--popup-normal')) {
		if ($('body').hasClass('has--popup-carreras')) {
			var nS = parseInt($('#popup--carreras').scrollTop());
			var tope = parseInt($('#popup--carreras > div').outerHeight()) - $h + (headerHeight * 2);
		} else if ($('body').hasClass('has--popup-normal')) {
			var nS = parseInt($('#popup--normal').scrollTop());
			var tope = parseInt($('#popup--normal > div').outerHeight()) - $h + (headerHeight * 2);
		}
		if (nS < tope) {
			//					$('.popup i.popup--close').css('transform', 'translateY(' + nS + 'px)');
		}
		if (nS > $h) {
			$('body').addClass('scrolled');
		} else {
			$('body').removeClass('scrolled');
		}
	} else {
		//				$('.popup i.popup--close').css('transform', '');
	}
}

function afterAjax() {
	resizable();
}

function resizable(viewportResize = true) {
	$w = window.innerWidth;
	$h = window.innerHeight;
	document.body.height = $h;
	$('html, body, .win--width, canvas').css('width', $w + 'px');
	$('html, body, .win--height, canvas').css('height', $h + 'px');
	headerHeight = parseInt($('#main_header').outerHeight());
	footerHeight = parseInt($('#main_footer').outerHeight());
	$(':root').css({
		'--headerheight': headerHeight + 'px',
		'--footerheight': footerHeight + 'px'
	});

	if ($w >= $h) {
		mapaWidth = $w * 2;
		mapaHeight = mapaWidth * mapaRatio;
		mapaStartZoom = mapaWidth * .2;
		if (($('#popup--normal .content .bloque.charlas').length || $('#popup--normal .content .bloque.fotos').length) && !sliderz) {
			$('.slider_holder').each(function () {
				if (($(this).hasClass('slider--charlas') && $('.slider', this).children().length > 3) || ($(this).hasClass('slider--galeria') && $('.slider', this).children().length > 2)) {
					if ($(this).hasClass('slider--charlas') && $('.slider', this).children().length > 3) {
						var countt = 3;
					} else {
						var countt = 2;
					}
					var sliderr = $('.slider', this);
					sliderr.slick({
						dots: false,
						arrows: true,
						speed: 750,
						pauseOnHover: true,
						autoplay: false,
						draggable: true,
						accessibility: false,
						centerMode: false,
						infinite: false,
						slidesToShow: countt,
						slidesToScroll: 1,
						prevArrow: '<button type="button" class="slick-prev slick-arrow"><i class="fas fa-chevron-left"></i></button>',
						nextArrow: '<button type="button" class="slick-next slick-arrow"><i class="fas fa-chevron-right"></i></button>',
						cssEase: 'cubic-bezier(0.77, 0, 0.175, 1)'
					});
					sliderz = true;
				}
			});
		}
	} else {
		mapaWidth = ((($h - (headerHeight + footerHeight)) * 2) / (mapaRatio * 10)) * 10;
		mapaHeight = ($h - (headerHeight + footerHeight)) * 2;
		mapaStartZoom = mapaWidth * .15;
		if (($('#popup--normal .content .bloque.charlas').length || $('#popup--normal .content .bloque.fotos').length) && sliderz) {
			$('.slider_holder').each(function () {
				if ($('.slick-list', this).length) {
					$('.slider', this).slick('unslick');
				}
			});
			sliderz = false;
			$('.slide').css('width', '');
		}
	}
	$('#sltr_menu').css({
		'top': headerHeight + 'px',
		'height': ($h - (headerHeight + footerHeight)) + 'px'
	});
	//			if (viewport && viewportResize) {
	//			$('#markers').css({
	//				'width': mapaWidth + 'px',
	//				'height': mapaHeight + 'px'
	//			});
	//				$('#markers').css('visibility', 'hidden');
	//				viewport.resize($w, $h - (headerHeight + footerHeight), mapaWidth, mapaHeight);
	//			}
}

function markersMove(x, y, clearInitial = false) {
	$('#markers').css('visibility', '');
	$('body').removeClass('markers--hidden');
	$('#markers').css('transform', 'translate3d(' + x + 'px, ' + y + 'px, 0)');
	if (clearInitial) {
		$('#preloader, #instrucciones').remove();
	}
}

function setVideos() {
	$('#popup--normal .bloque.videos').each(function () {
		$('ul li:first-of-type', this).addClass('active');
		$('.video_holder', this).html('<iframe src="https://www.youtube.com/embed/' + $('ul li:first-of-type button', this).attr('data-youtube') + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
	});
}

function setMenu() {
	$('#popup--menu .holder').append('<div class="menu" />');
	$('#popup--menu .menu').html($('#popup--normal .menu').html())
	$('.menu li:first-of-type').addClass('active');
}

function mapCreation() {
	if (!canvasCreated) {
		PIXI.utils.skipHello();
		app = new PIXI.Application({
			transparent: true,
			width: window.innerWidth,
			height: window.innerHeight - (headerHeight + footerHeight),
			resizeTo: window,
			autoResize: true,
			resolution: window.devicePixelRatio
		});
		$('#map').append(app.view);
		$('#markers').css({
			'width': mapaWidth + 'px',
			'height': mapaHeight + 'px'
		});
		viewport = app.stage.addChild(new Viewport.Viewport({
				screenWidth: window.innerWidth,
				screenHeight: window.innerHeight - (headerHeight + footerHeight),
				worldWidth: mapaWidth,
				worldHeight: mapaHeight
			}))
			.zoom(mapaStartZoom)
			.moveCenter(mapaWidth / 2, mapaHeight / 2)
			.drag({
				clampWheel: true,
				wheelScroll: .3
			})
			//					.pinch()
			.decelerate({
				friction: .8
			})
			.clamp({
				direction: 'all',
				underflow: 'center'
			})
			.on('moved', (data) => markersMove(data.viewport.x, data.viewport.y))
			.on('zoomed-end', (data) => markersMove(viewport.x, viewport.y, true));
		//				viewport.findCover(window.innerWidth, window.innerHeight);
		viewport.pause = true;
		app.renderer.interactive = true;
		assetsBase();
		canvasCreated = true;
	}
}

function assetsBase() {
	loader.add('mapa', './img/map.jpg')
		//				.add('vid1', './vid/vid1.mp4')
		.load((loader, resources) => {
			sprites.mapa = new PIXI.Sprite(resources.mapa.texture);
			//					sprites.vid1 = new PIXI.Sprite(resources.vid1.texture);
		});
	loader.onComplete.add(() => {
		var mapa = sprites.mapa;
		mapa.width = mapaWidth;
		mapa.height = mapaWidth * mapaRatio;
		viewport.addChild(mapa);
		//				rollMapVids();
		$('body').removeClass('loading');
	});
}

function rollMapVids() {
	const vid = PIXI.Texture.from(loader.resources.vid1.url);
	const vid1 = new PIXI.Sprite(vid);
	vid1Controller = vid1.texture.baseTexture.resource.source;
	vid1Controller.autoplay = false;
	vid1Controller.setAttribute('autoplay', false);
	vid1.width = mapaWidth * .3;
	vid1.height = mapaWidth * .3 * .5625;
	vid1.x = mapaWidth * .2;
	vid1.y = 150;
	vid1Controller.setAttribute('loop', true);
	vid1Controller.setAttribute('muted', true);
	viewport.addChild(vid1);
}

function todoeljs() {

	if ($('body').hasClass('debug')) {
		$('body').append('<div id="centercheck" />');
	}

	$('html, body').bind('scroll mousedown DOMMouseScroll mousewheel', function () {
		$('html, body, .popup').stop();
	});

	if ($('body').hasClass('desktop')) {
		$(document).mousemove(function (e) {
			if (!mouseMoving) {
				cursor.css('opacity', 1);
				cursorb.css('opacity', 1);
			}
			if (!$('body').hasClass('has--popup-carreras') && !$('body').hasClass('has--popup-normal')) {
				mouseMoving = true;
				mouseX = e.pageX;
				mouseY = e.pageY;
			}
		});
	}

	var ofst = 0;
	$('.marker').each(function () {
		$(this).css('transition-delay', ofst + 'ms');
		$('.curva', this).css('transition-delay', ofst + 'ms');
		$('.sign', this).css('transition-delay', ofst + 150 + 'ms');
		ofst += 50;
	});

	setMenu();
	setVideos();

	resizable(false);
	mapCreation();
}

function cursorAnimate(event) {
	distX = mouseX - cursorX;
	distY = mouseY - cursorY;
	cursorX = cursorX + (distX * cursorSpeed);
	cursorY = cursorY + (distY * cursorSpeed);
	if ((distX < -.01 || distX > .01 || distY < -.01 || distY > .01)) {
		gsap.to(cursor, {
			x: cursorX,
			y: cursorY,
			'overwrite': 'auto',
			duration: 0.0,
		});
		gsap.to(cursorb, {
			x: mouseX,
			y: mouseY,
			'overwrite': 'auto',
			duration: 0.0,
		});
	}
	requestAnimationFrame(cursorAnimate);
}

function rollCursors() {
	cursorAnimate();
	mouseX = window.innerWidth / 2;
	mouseY = window.innerHeight / 2;
}

$(document).ready(function () {

	if ($('body').hasClass('desktop')) {
		rollCursors();
	}

	$('.marker button').hover(function (e) {
		$('body').addClass('marker--hovered');
	}, function (e) {
		if (!$(this).hasClass('active')) {
			$('body').removeClass('marker--hovered');
		}
	});
	$(document).on('click', '.marker button', function (e) {
		$('body').addClass('marker--hovered');
		$('.marker, .marker .curva, .marker .sign').css('transition-delay', '');
		$(this).parent().addClass('active');
		$(this).parent().siblings('.active').removeClass('active');
		if ($(this).parent().hasClass('carreras')) {
			$('body').attr('data-openmarker', $(this).parent().attr('id'));
		}
		if (!$('body').hasClass('from--carreras')) {
			$('body').addClass('popup--delay');
		} else {
			$('body').addClass('popup--delay2');
		}
		setTimeout(function () {
			$('body').removeClass('popup--delay popup--delay2');
		}, duration * 4);
		if ($h > $w) {
			activeMarkerOffset = 0;
		} else {
			if ($(this).parent().hasClass('carreras')) {
				activeMarkerOffset = 0;
			} else {
				activeMarkerOffset = $w / 5;
			}
		}
		viewport.animate({
			position: new PIXI.Point(parseInt($(this).parent().css('left')) + activeMarkerOffset, parseInt($(this).parent().css('top'))),
			time: duration * 4,
			ease: 'easeInOutQuart'
		});
		$('body').addClass('map--opening map--opened');
		e.preventDefault();
	});
	$(document).on('pointerdown', '#instrucciones button', function () {
		$('body').removeClass('has--instrucciones');
		if (vid1Controller) {
			vid1Controller.play();
		}
		viewport.pause = false;
		viewport.snapZoom({
			width: window.innerWidth,
			time: duration * 6,
			ease: 'easeInOutQuart'
		});
	});

	$(document).on('click', '.btn--popup-carreras', function (e) {
		e.preventDefault();
		setTimeout(function () {
			viewport.pause = true;
		}, duration * 4);
		$('body').addClass('has--popup-carreras');
	});

	$(document).on('click', '.btn--popup-normal', function (e) {
		e.preventDefault();
		setTimeout(function () {
			viewport.pause = true;
		}, duration * 4);
		var thiss = $(this);
		if (thiss.hasClass('no--menu')) {
			$('body').addClass('no--menu');
		} else {
			$('body').removeClass('no--menu');
		}
		$('body').addClass('has--popup-normal');
		if ($('body').hasClass('has--popup-carreras')) {
			$('body').addClass('from--carreras');
			$('body').removeClass('has--popup-carreras');
			$('body').addClass('popup--delay2');
		}
		setTimeout(function () {
			$('body').removeClass('popup--delay2');
		}, duration * 2);
	});

	$(document).on('click', '.popup--close', function (e) {
		$('body').removeAttr('data-openmarker');
		$('body').removeClass('has--popup-carreras has--popup-normal map--opening map--opened marker--hovered scrolled');
		$('.marker.active').removeClass('active');
		viewport.pause = false;
		setTimeout(function () {
			$('body').removeClass('from--carreras no--menu');
			$('.popup').removeClass('rolled--in');
			$('.popup').animate({
				scrollTop: 0
			}, 0);
		}, duration);
	});

	$(document).on('click', '.popup--back', function (e) {
		$('body').removeClass('has--popup-normal');
		$('#' + $('body').attr('data-openmarker') + ' button').click();
		setTimeout(function () {
			$('.popup').animate({
				scrollTop: 0
			}, 0);
		}, duration);
	});

	$(document).on('click', '.bloque.videos button', function (e) {
		$(this).parent().parent().addClass('active');
		$(this).parent().parent().siblings('.active').removeClass('active');
		$(this).parent().parent().parent().siblings('.video_holder').html('<iframe src="https://www.youtube.com/embed/' + $(this).attr('data-youtube') + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
		console.log($(this).parent().parent().parent().parent().parent().offset().top * -1);
		$('#popup--normal').animate({
			scrollTop: ($(this).parent().parent().parent().parent().parent().offset().top - $('#popup--normal').scrollTop() * -1) - (headerHeight + footerHeight)
		}, duration * 4, 'easeInOutQuart');
	});

	$(document).on('click', '.menu button', function (e) {
		$(this).parent().addClass('active');
		$(this).parent().siblings('.active').removeClass('active');
		$('#popup--normal').animate({
			scrollTop: 0
		}, 0);
		$('body').addClass('loading--interior');
		// AQUI LLAMAR EL AJAX Y CUANDO HAGA EL success QUITAR LA CLASE 'loading--interior' AL body con:
		// $('body').removeClass('loading--interior');
	});

	$(document).on('click', '.uc-navbar_mobile-button', function (e) {
		$('body').toggleClass('open--menu');
	});

	$(document).on('click', '#to--top', function (e) {
		$('#popup--normal').animate({
			scrollTop: 0
		}, duration * 4, 'easeInOutQuart');
	});

	if ($('body').hasClass('desktop')) {
		$(document).on('taphold', '#map canvas', {
				delay: 200
			}, function () {
				$('body').addClass('map--grabbing');
			})
			.on('pointerup', function () {
				$('body').removeClass('map--grabbing');
			});
	}

	todoeljs();
});

$(window).resize($.debounce(duration, resizable));
$('#popup--normal').scroll($.throttle(100 / 60, checkScroll));
$('#popup--carreras').scroll($.throttle(100 / 60, checkScroll));
