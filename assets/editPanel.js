$('.editPanel .editProfile .info-basicInfo').css({'display' : 'inline'});

$('.editPanel .navBar li').click(function () {
	// console.log(1111);
	if (!$(this).hasClass('selected')) {
		$(this).parent().parent().parent().children('.content').children('.navBar').children('.selected').removeClass('selected');
		$(this).addClass('selected');

		classname = $(this).attr('id').replace('navBar-', 'info-');
		// console.log(classname)
		$(this).parent().parent().parent().children('.content').children('.info').addClass('info-hiden');
		$(this).parent().parent().parent().children('.content').children('.' + classname).removeClass('info-hiden');

		// 
		// $('.editPanel .navBar .selected')

		// $(this).addClass('selected');
		
	}
})

// Закрытие окна настроек
$(document).on('click', '.editPanel .header img', function () {
	$('body').css({'overflow' : 'unset'});
	$('.editPanel').removeClass('editPanel-opened');
	$('body').css({"overflow" : "unset"})
})