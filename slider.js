(function () {
	'use strict';
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	document.querySelectorAll('.tlal-slider').forEach(function (root) {
		var slides = root.querySelectorAll('.tlal-slide');
		var dots = root.querySelectorAll('.tlal-slider__dot');
		var prev = root.querySelector('.tlal-slider__nav--prev');
		var next = root.querySelector('.tlal-slider__nav--next');
		var n = slides.length;
		var cur = 0;
		if (n < 2) return;

		function show(i) {
			i = (i + n) % n;
			if (i === cur) return;
			[slides, dots].forEach(function (list) {
				if (!list[cur] || !list[i]) return;
				list[cur].classList.remove('is-active');
				list[i].classList.add('is-active');
			});
			slides[cur].setAttribute('aria-hidden', 'true');
			slides[cur].setAttribute('inert', '');
			slides[i].removeAttribute('aria-hidden');
			slides[i].removeAttribute('inert');
			if (dots[cur]) dots[cur].removeAttribute('aria-current');
			if (dots[i]) dots[i].setAttribute('aria-current', 'true');
			// Load the following image early so the next fade is not blank.
			var img = slides[(i + 1) % n].querySelector('img[loading="lazy"]');
			if (img) img.loading = 'eager';
			cur = i;
		}

		if (prev) prev.addEventListener('click', function () { show(cur - 1); restart(); });
		if (next) next.addEventListener('click', function () { show(cur + 1); restart(); });
		dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i); restart(); }); });
		root.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowRight') { show(cur + 1); restart(); }
			if (e.key === 'ArrowLeft') { show(cur - 1); restart(); }
		});

		// Touch swipe.
		var x0 = null;
		root.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; stop(); }, { passive: true });
		root.addEventListener('touchend', function (e) {
			if (x0 === null) return;
			var dx = e.changedTouches[0].clientX - x0;
			if (Math.abs(dx) > 40) show(cur + (dx < 0 ? 1 : -1));
			x0 = null;
			start();
		}, { passive: true });

		var delay = parseInt(root.getAttribute('data-autoplay'), 10) || 0;
		var timer = null;
		function stop() { if (timer) clearInterval(timer); timer = null; }
		function start() { stop(); if (delay > 0 && !reduce) timer = setInterval(function () { show(cur + 1); }, delay); }
		function restart() { if (timer) start(); }

		root.addEventListener('mouseenter', stop);
		root.addEventListener('mouseleave', start);
		root.addEventListener('focusin', stop);
		root.addEventListener('focusout', start);
		document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
		start();
	});
})();
