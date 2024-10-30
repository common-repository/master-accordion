(function( $ ) {
	'use strict';

	var mran_accordion={

		Snipits:{

			appendOnLoad: function(){

				$('.mran_accordion_nav_menu').each(function(){
					var mran_icons = $(this).find('.mran-menu-toggle-wraper .mran-toggle-icon').clone();
					if(!$(this).find('.menu-item-has-children>.mran-toggle-icon').length){
						$(this).find('.menu-item-has-children>a').after(mran_icons);
					}
				});
				
			},

			tabEqualHeight:function(mran_tab){

				var mran_tab_list, 
				tab_content_wraper, 
				tab_title_height, 
				tab_content_height, 
				tab_max_height;

				if( !mran_tab.hasClass('horizontal') ){
					return;
				}			

				mran_tab_list = mran_tab.find('.mran-tab-list');
				tab_content_wraper = mran_tab.find('.mran-tab-content-wraper');

				mran_tab_list.css({'min-height': ''});
				tab_content_wraper.css({'min-height': ''});

				tab_max_height = mran_tab.height();

				mran_tab_list.css({'min-height' : tab_max_height});
				tab_content_wraper.css({'min-height' : tab_max_height});

			},

			Horizontal:function(accordion_horizontal){
				accordion_horizontal = false;
				if(!accordion_horizontal){
					accordion_horizontal = $('.mran-accordion.horizontal');
				}
				accordion_horizontal.each(function(){

					var itemsSelector, accordion_list_width, activeWidth, maxHeight= 0, mran_title_width=0;
					itemsSelector = $(this).find('.mran-accordion-item-wrap');
					itemsSelector.children('.mran-content').css('height', '');
					itemsSelector.children('.mran-accordion-title').css('height', '');
					itemsSelector.each(function(){
						mran_title_width += $(this).children('.mran-accordion-title').outerWidth()+2;
					});
					
					accordion_list_width = $(this).find('.mran-accordion-list').outerWidth();
					activeWidth = (accordion_list_width>mran_title_width) ? accordion_list_width-mran_title_width : 0;
					if(activeWidth){
						$(this).find('.mran-content.current').css('width', activeWidth);
					}
					maxHeight = $(this).find('.mran-accordion-list').height();
					if(maxHeight){
						itemsSelector.children('.mran-accordion-title').css('height', maxHeight);
						itemsSelector.children('.mran-content').css('height', maxHeight);
					}

				});

			},

			jsMenuAccordion: function($this){

				var mran_dropdown_icon, mran_active_dp_icon;
				mran_dropdown_icon = $this.data('dropdown-icon');
				mran_active_dp_icon = $this.data('active-dp-icon');
				if($this.closest('.mran-accordion').hasClass('vertical')){
					if($this.hasClass('current')){
						$this.removeClass(mran_active_dp_icon).addClass(mran_dropdown_icon);
					}else{
						$this.removeClass(mran_dropdown_icon).addClass(mran_active_dp_icon);
					}

					$this.toggleClass('current').siblings('.sub-menu').slideToggle();

				}
				
			},

		},

		Click: function(){

			$('.mran_accordion_nav_menu').on('click', '.mran-toggle-icon', function(evt){
				mran_accordion.Snipits.jsMenuAccordion( $(this) );
			});

			$('.mran-tab-list .mran-tab-title').click(function(evt){
				evt.preventDefault();
				var tab_list, tab_id, content_wraper, link_tag, tab_title;
				tab_title = $(this);
				if(tab_title.hasClass('current')){
					return;
				}
				link_tag = tab_title.find('.mran-post-link');
				tab_id = link_tag.attr('href');
				tab_list = link_tag.closest('.mran-tab-list');
				content_wraper = tab_list.siblings('.mran-tab-content-wraper');

				tab_title.toggleClass('current').closest('li').siblings('li').find('.mran-tab-title').removeClass('current');
				content_wraper.find(tab_id).siblings('.mran-tab-content').removeClass('current');
				content_wraper.find(tab_id).addClass('current');

				mran_accordion.Snipits.tabEqualHeight($(this).closest('.mran-tab'));

			});

			$('.mran-accordion .mran-accordion-title').click(function(evt){

				evt.preventDefault();
				var accordion_list, accordion_sibilings_list, accordion_wraper, accordion_wraper_width,
					accordion_title_width, mran_content_width, mran_toogle_icon, mran_dropdown_icon, mran_active_dp_icon;
				accordion_list = $(this).closest('.mran-accordion-item-wrap');
				accordion_sibilings_list = accordion_list.siblings('.mran-accordion-item-wrap');
				accordion_wraper = $(this).closest('.mran-accordion-list');
				mran_toogle_icon = $(this).find('.mran-toggle-icon');
				mran_dropdown_icon = (mran_toogle_icon.data('dropdown-icon') ) ? mran_toogle_icon.data('dropdown-icon') : '';
				mran_active_dp_icon = (mran_toogle_icon.data('active-dp-icon') ) ? mran_toogle_icon.data('active-dp-icon') : '';
				if(accordion_wraper.hasClass('disabled')){
					return false;
				}else{
					accordion_wraper.addClass('disabled');
				}

				$(this).toggleClass('current');
				if($(this).closest('.mran-accordion').hasClass('vertical')){
					$(this).siblings('.mran-content').slideToggle().toggleClass('current');
					accordion_sibilings_list.find('.mran-accordion-title').removeClass('current');
					accordion_sibilings_list.find('.mran-content').slideUp().removeClass('current');
				}else{
					accordion_wraper_width = accordion_wraper.outerWidth();
					accordion_title_width = 0;
					accordion_wraper.find('.mran-accordion-title').each(function(){
						accordion_title_width += $(this).outerWidth()+2;
					});
					mran_content_width = (accordion_wraper_width>accordion_title_width) ? accordion_wraper_width - accordion_title_width : 0;

					accordion_sibilings_list.find('.mran-accordion-title').removeClass('current');
					accordion_sibilings_list.find('.mran-content').animate({width: 0}, 400, function(){ 
						$(this).removeClass('current');
						$(this).css('display', 'none');
					});
					if($(this).siblings('.mran-content').hasClass('current')){
						$(this).siblings('.mran-content').animate({width: 0}, 400, function(){ 
							$(this).removeClass('current'); 
							$(this).css('display', 'none');
						});
					}else{
						$(this).siblings('.mran-content').css('display', 'block')
						.addClass('current').animate({width: mran_content_width}, 400, function(){
							// After Content width Increased
						});
					}

				}
				if(mran_toogle_icon.hasClass(mran_dropdown_icon)){
					mran_toogle_icon.removeClass(mran_dropdown_icon).addClass(mran_active_dp_icon);
					accordion_sibilings_list.find('.mran-toggle-icon').removeClass(mran_active_dp_icon).addClass(mran_dropdown_icon);
				}else{
					mran_toogle_icon.removeClass(mran_active_dp_icon).addClass(mran_dropdown_icon);
					accordion_sibilings_list.find('.mran-toggle-icon').removeClass(mran_active_dp_icon).addClass(mran_dropdown_icon);
				}
				setTimeout(function(){
					mran_accordion.Snipits.Horizontal($(this).closest('.mran-accordion.horizontal'));
					accordion_wraper.removeClass('disabled');
				}, 400);

			});

		},

		Ready: function(){
			mran_accordion.Snipits.appendOnLoad();
			mran_accordion.Snipits.Horizontal(false);
			mran_accordion.Click();
		},

		Load: function(){
			mran_accordion.Snipits.Horizontal(false);
		},

		Resize: function(){
			mran_accordion.Snipits.Horizontal(false);
		},

		Scroll: function(){

		},

		Init: function(){

			var ready, load, resize, scroll, _this=mran_accordion;
			ready=_this.Ready, load=_this.Load, resize=_this.Resize;

			$(document).ready(ready);
			$(window).load(load);
			$(window).resize(resize);
			$(window).scroll(scroll);

		}
	};

	mran_accordion.Init();

})( jQuery );
