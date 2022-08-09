// Loading
$(function() {
	$("#loading-wrapper").fadeOut(1000);
});


// Toggle sidebar
$("#toggle-sidebar").on('click', function () {
	$(".page-wrapper").toggleClass("toggled");
});


// Toggle graph day selection
$(function() {
	$(".graph-day-selection .btn").on('click', function () {
		$(".graph-day-selection .btn").removeClass("active");
		$(this).addClass("active");   
	});
});

// Todays Date
$(function() {
	var interval = setInterval(function() {
		var momentNow = moment();
		$('#todays-date').html(momentNow.format('DD MMMM YYYY'));
	}, 100);
});


// Todo list
$('.todo-body').on('click', 'li.todo-list', function() {
	$(this).toggleClass('done');
});

// Chat App
$(".users-container .users-list li").on('click', function () {
	$(".empty-chat-screen").addClass("d-none");
	$(".chat-content-wrapper").removeClass("d-none");
	$(".users-container .users-list li").removeClass("active-chat");
	$(this).addClass("active-chat");
});

// Task App
(function($) {
	var checkList = $('.task-checkbox'),
	toDoCheck = checkList.children('input[type="checkbox"]');
	toDoCheck.each(function(index, element) {
		var $this = $(element),
		taskItem = $this.closest('.task-block');
		$this.on('click', function(e) {
			taskItem.toggleClass('task-checked');
		});
	});
})(jQuery);


// Task App
$(function() {
	$(".task-actions a.important").on('click', function () {
		$(this).toggleClass("active");
	});
});
$(function() {
	$(".task-actions a.star").on('click', function () {
		$(this).toggleClass("active");
	});
});
$(function() {
	$(".task-action-items a.mark-done-item").on('click', function () {
		$( event.target ).closest( ".task-block" ).toggleClass( "task-checked" );
	});
});
$(function() {
	$(".task-action-items a.delete-task-item").on('click', function () {
		$( event.target ).closest( ".task-block" ).remove();
	});
});





// Custom Default/Compact/Pinned Sidebars JS
jQuery(function ($) {

	// Default Compact menu
	$(".default-sidebar-dropdown > a").on('click', function () {
		$(".default-sidebar-submenu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".default-sidebar-dropdown").removeClass("active");
			$(this).parent().removeClass("active");
		} else {
			$(".default-sidebar-dropdown").removeClass("active");
			$(this).next(".default-sidebar-submenu").slideDown(200);
			$(this).parent().addClass("active");
		}
	});


	// Compact menu
	$(".compact-sidebar-dropdown > a").on('click', function () {
		$(".compact-sidebar-submenu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".compact-sidebar-dropdown").removeClass("active");
			$(this).parent().removeClass("active");
		} else {
			$(".compact-sidebar-dropdown").removeClass("active");
			$(this).next(".compact-sidebar-submenu").slideDown(200);
			$(this).parent().addClass("active");
		}
	});

	// Dropdown menu	
	// $(".header-actions > li.dropdown").on('click', function () {		
	// 	console.log("Anjing");
	// 	if ($(".header-actions > li.dropdown").hasClass("show")) {
	// 		console.log("Ada");
	// 		$(".header-actions > li.dropdown").removeClass("show");
	// 	} else {
	// 		console.log("Tidak");
	// 		$(".header-actions > li.dropdown").addClass("show");
	// 	}
	// });
	
	// Dropdown User Settings
	const dropdown = document.querySelector('.header-actions > li.dropdown');
	const user_settings = document.querySelector('.user-settings');

	// Dropdown Notifications
	const dropdown_notifications = document.querySelector('.header-actions > li.dropdown-notifications');
	const user_settings_notifications = document.querySelector('#notifications');

	dropdown.onclick = function() {
		if (dropdown.classList.contains('show')) {
			// console.log('ada');
			dropdown.classList.remove('show');
			user_settings.classList.remove('bg-white');
		} else {
			// console.log('tidak ada');
			dropdown.classList.add('show');
			user_settings.classList.add('bg-white');

			dropdown_notifications.classList.remove('show');
			user_settings_notifications.classList.remove('bg-white');
		}
	}

	dropdown_notifications.onclick = function() {
		if (dropdown_notifications.classList.contains('show')) {
			// console.log('ada');
			dropdown_notifications.classList.remove('show');
			user_settings_notifications.classList.remove('bg-white');
		} else {
			// console.log('tidak ada');
			dropdown_notifications.classList.add('dropdown');
			dropdown_notifications.classList.add('show');
			user_settings_notifications.classList.add('bg-white');

			dropdown.classList.remove('show');
			user_settings.classList.remove('bg-white');
		}
	}

	// Pinned sidebar
	$(function() {
		$(".slim-sidebar");
		$(".default-sidebar-wrapper").on('hover', function () {
				console.log("mouseenter");
				$(".slim-sidebar").addClass("sidebar-hovered");
			},
			function () {
				console.log("mouseout");
				$(".slim-sidebar").removeClass("sidebar-hovered");
			}
		)
	});


	// Added by Srinu 
	$(function(){
		// When the window is resized, 
		$(window).resize(function(){
			// When the width and height meet your specific requirements or lower
			if ($(window).width() <= 768){
				$(".page-wrapper").removeClass("pinned");
			}
		});
		// When the window is resized, 
		$(window).resize(function(){
			// When the width and height meet your specific requirements or lower
			if ($(window).width() >= 768){
				$(".page-wrapper").removeClass("toggled");
			}
		});
	});


});





/***********
***********
***********
	Bootstrap JS 
***********
***********
***********/

// Tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Popover
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	return new bootstrap.Popover(popoverTriggerEl)
})

// Tabs on Hover
// jQuery('.sidebar-tabs .nav a.nav-link').hover(function(e){
// 	e.preventDefault();
// 	jQuery('.tab-pane').removeClass('active');
// 	tabContentSelector = jQuery(this).attr('href');
// 	jQuery(this).tab('show');
// 	jQuery(tabContentSelector).addClass('active');
// });

$(function () {
  $('.sidebar-tabs a[data-toggle="tooltip"]').tooltip({
		placement: 'bottom'
	});
})