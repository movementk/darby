/*
* Touch Image Gallery (c) Dynamic Drive (www.dynamicdrive.com)
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
* Requires: jQuery 1.7 or higher
* Requires: jquery.touchSwipe.min.js
* Last updated: Aug 3rd, 2013:
* - Clicking on forward/back nav buttons no longer selects entire DIV
*/

(function($){

	var gallerydefaults = {
		width: 300,
		height: 250,
		navarea: '20%',
		curslide: 0,
		threshold: 75
	}

	var swipeOptions={
		triggerOnTouchEnd : true,
		triggerOnTouchLeave : true,
		allowPageScroll:"vertical"
	}

	var transform = typeof $(document.documentElement).css('transform') != "undefined" // test for support for CSS3 transform
	var detecttouch = !!('ontouchstart' in window) || !!('ontouchstart' in document.documentElement) || !!window.ontouchstart || !!window.Touch || !!window.onmsgesturechange || (window.DocumentTouch && window.document instanceof window.DocumentTouch)

	$.fn.touchgallery = function(options){
		return this.each(function(){
			var setting = $.extend({}, gallerydefaults, options)
				$slideshow = $(this),
				swipestart = false,
				initialx = 0,
				dist = 0,
				swipecount = 0
			var $belt = $slideshow.find('ul:eq(0)')
			$belt.moveit = function(val){
				//if (transform)
					//this.css('transform', 'translateX(' + val + 'px)')
				//else
					this.css('left', val)
				this.data('curleft', val)			
			}
			var $lis = $belt.find('li')
			var imgcount = $lis.length
			var $jumptodiv = $('<div class="jumptodiv" />')
				.html(function(){
					var bullets =''
					for (var i=0; i<imgcount; i++){
						bullets += '<span data-image="' + i + '" style="cursor: pointer">&#9679;</span> '
					}
					return bullets
				}())
				.appendTo($slideshow)
			var $jumptobuttons = $jumptodiv.find('span')

			function navigate(keyword){
				clearTimeout(setting.hidenavtimer)
				var curslide = setting.curslide 
				switch (keyword){
					case 'forth':
						setting.curslide = (curslide < imgcount-1)? curslide + 1 : 0
						break
					case 'back':
						setting.curslide = (curslide >0)? curslide - 1 : imgcount-1
						break
					default:
						setting.curslide = parseInt(keyword)
				}
				$jumptobuttons.removeClass('selected').eq(setting.curslide).addClass('selected')
				$belt.moveit( -setting.width * setting.curslide )
			}
	
			$slideshow.add($lis).css({
				width: setting.width,
				height: setting.height
			})
	
			$belt.css({width: setting.width * imgcount})
			navigate(setting.curslide)
	
			swipeOptions.swipeStatus = function(event, phase, direction, distance){
				if (phase == 'start'){
					swipestart = true
					swipecount = 0
					initialx = parseInt( $belt.data('curleft') )
					dist = 0
				}
				else if (phase == "move" && swipestart){
					dist = (direction == 'left'? -1 : 1) * distance + initialx
					$belt.moveit( Math.min(dist, (setting.curslide+1) * setting.width) )
				}
				else if (phase == 'cancel'){
					$belt.moveit( -setting.width * setting.curslide )
				}
				else if (phase == 'end'){
					if (distance < setting.threshold){ // snap back
						navigate(setting.curslide)
					}
					else{
						swipecount ++
						if (swipecount == 1){
							navigate( (direction == 'left')? 'forth' : 'back' )
						}
					}
					swipestart = false
				}
			}

			$slideshow.swipe(swipeOptions)

			$jumptodiv.swipe({
				tap: function(e){
					var target = e.target || e.srcElement
					if (target.tagName == 'SPAN'){
						var $target = $(target)
						navigate( parseInt($target.data('image')) )
					}
				}
			})
		})
	}


}) (jQuery)