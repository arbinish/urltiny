var windoWidth;
var windowHeight;
var mainWidth;
var leftPos;
var topPos;

setupOutWindow = function(data) {
	$('#p').remove();
	var content = '<img src="/icons/hand.right.png" style="margin-right:1em; position:absolute;" />';
	$('<div id="p"></div>').insertAfter('#main').html(data);
	console.log('top = '  + topPos + ' left = ' + leftPos);	 
	$(content).insertBefore('#p');
	$('img').css({'top': topPos + 10, 'left': leftPos - 30 });
	$('div#p').css({'top': topPos, 'left': leftPos}).fadeIn();
}

processInputData = function() {
	var url = $('#main input').val();
	if (!url) {
		setupOutWindow('Enter a URL');
		$('#main input').focus();
		return false;
	}
	
	$.get(location.pathname, 
		{'q': url}, 
		function(data) { 
				setupOutWindow(document.URL + data['uid']);
/*					console.log(data);  */
		}, 
		'json'
	);
}

$(document).ready(function(e){
	windowHeight = $(window).height();
	windoWidth = $(window).width();	
	mainWidth = Math.floor(windoWidth * 0.6);	
	var inputWidth = Math.floor(mainWidth * 0.7);	
	$('#main').css('width', mainWidth);
	$('#main input').css('width', inputWidth);
/*	$('#footer').width(inputWidth); */
	$('#main input').focus();
	var ele = $('#main input')[0];
	leftPos = Math.ceil(($(ele).position())['left']);
	ele = $('#main')[0];
	topPos = Math.ceil(($(ele).position())['top']) + 10;
/*	console.log('input width = ' + inputWidth); */
	$('#main a').click(function(e){
		e.preventDefault();
		processInputData();
	});
	$('input[name="url"]').keydown(function(e) {
		if (e.which == 13) {
			processInputData();
		}
	});
});

