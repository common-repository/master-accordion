(function( $ ) {
	'use strict';
	var mran_accordion={

		Snipits: {

			Shortcode_Generator: function(formData){
				var formValues = {};
				for(var i=0; i<formData.length; i++){
					formValues[formData[i].name] = formData[i].value;
				}
				var shortcodes = '[';
				shortcodes += formValues.mran_tab_or_accordion;
				switch(formValues.mran_tab_or_accordion){
					case "mran_tab":
						delete formValues.mran_active_dp_icon;
						$('#mran_active_dp_icon').closest('.mran-field-container').css('display', 'none');
						break;
					case "mran_accordion":
						$('#mran_active_dp_icon').closest('.mran-field-container').css('display', 'table-row');
						break;
					default:
						break;

				}

				delete formValues.mran_tab_or_accordion;

				for(var key in formValues){
					if(formValues[key]){
						shortcodes += ' '+key+'="' + formValues[key] + '"';
					}
				}
				shortcodes += ']';
				
				console.log(formValues);
				$('.mran_generated_shortcode').html(shortcodes);
			},

			Color_Icon_Picker: function(){
				var colorpicker, icon_picker;
				colorpicker = {
					change: function(evt, ui){
						$(evt.target).val(ui.color.toString()).trigger('change');
					}
				};
				icon_picker = {
					hideOnSelect: true
				};

				$('.widget-liquid-right .mran_color_picker, .mran_generater_wraper .mran_color_picker').wpColorPicker(colorpicker);
				$('.mran_icon_picker').iconpicker(icon_picker);
				$(document).on('widget-updated widget-added', function(e, widget){
                	widget.find('.mran_color_picker').wpColorPicker(colorpicker);
                	widget.find('.mran_icon_picker').iconpicker(icon_picker);

            	}); 

            	$(document).on('widget-added', function(e, widget){
                	widget.find('.mran-widget-post-type').trigger('change');

            	}); 

            	$(document).on('iconpickerSelected', '.mran_icon_picker', function(event){
  					$(this).trigger('change');
				});

				$(document).on('change', '.mran_icon_picker', function(){

					var previous_class, current_class, has_attr, mran_icon;
					current_class	= this.value;
					mran_icon = $(this).siblings('.mran_icon');
					has_attr = $(this).attr('data-previous');
					if(has_attr!="undefined"){
						previous_class	= $(this).attr('data-previous');
					}else{
						previous_class	= this.defaultValue;
					}
					$(this).attr('data-previous', current_class);
					mran_icon.removeClass(previous_class).removeClass(function(idx, cls){
						return (cls.match(/\bfa-\S+/g) || []).join(' ');
					});
					console.log(current_class);
					mran_icon.addClass(current_class);

				});

			},

			Accordion_Widget: {

				Ajax_Data: function(accordion_data, append_to){
					var accordion_ajax_url = window.location.origin+ajaxurl;
					$.post(accordion_ajax_url,{
							'action': 'mran_accordion_widget',
							'data':   accordion_data
						},
						function(response){
							var change_html = mran_accordion.Snipits.Accordion_Widget.Change_Widget_Data;
							change_html(append_to, response);
						}
					);
				},

				Change_Data: function(selector){
					var data, ajax_result,
						append_to=selector.data('accordion-change-id'),
						accordion_widget = mran_accordion.Snipits.Accordion_Widget;
					data = {
						data_value: selector.val(),
						data_type: selector.data('accordion-value'),
					};
					accordion_widget.Ajax_Data(data, append_to);
				},

				Change_Widget_Data: function(append_to, ajax_result){
					var options, data_obj;
					data_obj=JSON.parse(ajax_result);
					options+='<option value="" selected="selected">No Filter</option>';
					$.each(data_obj, function(key, value){
						options+='<option value="'+value.slug+'">'+value.name+'</option>';
					});
					$(append_to).html(options);
					$(append_to).val('').trigger('change');
				},

			},

		},

		MouseEvents: function(){
			var _this=mran_accordion, widget=_this.Snipits.Accordion_Widget;
			$(document).on('change', '.mran-widget-post-type, .mran-widget-taxonomy', function(evt){
				widget.Change_Data($(this));
			});
			$(document).on('click', '.mran-tab-list .nav-tab', function(evt){
				if(!$(this).hasClass('nav-tab-active')){
					var tab_wraper, tab_id;
					tab_id = $(this).data('id');
					tab_wraper = $(this).closest('.mran-tab-wraper');
					$(this).addClass('nav-tab-active').siblings('.nav-tab').removeClass('nav-tab-active');
					tab_wraper.find('.mran-tab-content').removeClass('mran-content-active');
					tab_wraper.find(tab_id).addClass('mran-content-active');
				}
			});
			$('#mran_generate_button').on('click', function(){
				var formData = $(this).closest('form').serializeArray();
				mran_accordion.Snipits.Shortcode_Generator(formData);
			});

			$('#mran_shortcode_generator_form').on('change', function(){
				$('#mran_generate_button').trigger('click');
			});
		},

		Ready: function(){
			var _this=mran_accordion;
			_this.Snipits.Color_Icon_Picker();
			_this.MouseEvents();
		},

		Load: function(){
			var _this=mran_accordion;

		},

		Scroll: function(){
			var _this=mran_accordion;

		},

		Resize: function(){
			var _this=mran_accordion;

		},

		Init: function(){
			var ready, load, scroll, resize, _this=mran_accordion;
			ready=_this.Ready, load=_this.Load, resize=_this.Resize;
			$(document).ready(ready);
			$(window).load(load);
			$(window).resize(resize);
			$(window).scroll(scroll);
		}

	};

	mran_accordion.Init();


})( jQuery );
