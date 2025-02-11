// Demo 3 Js file
$(document).ready(function () {
	'use strict';

	// Deal of the day countdown
	if ($.fn.countdown) {
		$('.deal-countdown').each(function () {
			var $this = $(this),
				untilDate = $this.data('until'),
				compact = $this.data('compact');

			$this.countdown({
				until: untilDate, // this is relative date +10h +5m vs..
				format: 'HMS',
				padZeroes: true,
				labels: ['years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds'],
				labels1: ['year', 'month', 'week', 'day', 'hour', 'minutes', 'second']
			});
		});

		// Pause
		// $('.deal-countdown').countdown('pause');
	}
});
document.addEventListener("DOMContentLoaded", function () {
	document.querySelectorAll('.parent-category').forEach(function (category) {
		category.addEventListener('click', function () {
			const subCategories = this.nextElementSibling;
			const toggleIcon = this.querySelector(
				'.toggle-icon'); // Tìm thẻ <a> bên trong category

			if (subCategories.style.maxHeight === '0px' || subCategories.style.maxHeight ===
				'') {
				// Mở rộng subcategories với max-height đủ lớn
				subCategories.style.display = 'block';
				subCategories.style.maxHeight = subCategories.scrollHeight + 'px';
				this.classList.add('open');
				toggleIcon.classList.add('collapsed'); // Thêm class "collapsed" vào thẻ <a>
			} else {
				// Thu gọn subcategories về max-height = 0
				subCategories.style.maxHeight = '0';
				setTimeout(() => {
					subCategories.style.display = 'none';
				}, 300); // Ẩn hoàn toàn sau khi animation kết thúc
				this.classList.remove('open');
				toggleIcon.classList.remove(
					'collapsed'); // Xóa class "collapsed" khỏi thẻ <a>
			}
		});
	});
});
