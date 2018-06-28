{literal}
<style type="text/css">
	#videoholder{display: none;}
	.fancybox-wrap{opacity: 1 !important;}
</style>
{/literal}
{if $status_video && $is_module_enable}
	<script type="text/javascript">
		$('#thumbs_list_frame li:last()').after(`<li id="thumb_video_aymatic" class="aymatic_video_link"><a id="aymatic_thumb_link" href="{$video_thumbnialurl}" 
		data-video="{$video_url}" class="youtube-video videocenter">
		<img id="thumb_image" class="img-responsive video-thumb" src="{$video_thumbnialurl}"/>
		<img style="background:none;position:absolute;top:15%;right:15%;width:70%;height:70%" class="aymatic_play_icon" src="../modules/aymaticprestashop/css/images/baseline-play_circle_outline-24px.svg"/>
		</a></li>`);

		$("a.youtube-video").on("click", function(){
			$.fancybox({
				href: $(this).data("video"),
				type: 'iframe'
			}); // fancybox
			return false;
		}); // on

		$('#thumbs_list_frame a').mouseenter(function(){
			if($(this).attr('id') === 'aymatic_thumb_link'){
				displayIcon();
			}
			else{
				hideIcon();
			}
		});
		$('#thumbs_list_frame a').mouseleave(function(){
			if($('#thumb_video_aymatic a').hasClass('shown')){
				displayIcon();
			}
			else{
				hideIcon();
			}
		});

		$('#bigpic').mouseenter(function(){
			$('#big_play_icon')[0].style.opacity = "0.6";
		});
		$('#bigpic').mouseleave(function(){
			$('#big_play_icon')[0].style.opacity = "1.0";
		});
		$('#big_play_icon').mouseenter(function(){
			$('#big_play_icon')[0].style.opacity = "0.6";
		});
		$('#big_play_icon').mouseleave(function(){
			$('#big_play_icon')[0].style.opacity = "1.0";
		});

		function displayIcon(){
			$('#big_play_icon')[0].style.display = "inline";
			$('#bigpic')[0].style.opacity = "0.5";
		}
		function hideIcon(){
			$('#big_play_icon')[0].style.display = "none";
			$('#bigpic')[0].style.opacity = "1.0";
		}

		$(document).ready(function(){
			if(!$('#big_play_icon').length){
				$('#bigpic').after( `<img id="big_play_icon" style="background:none;position:absolute;top:25%;right:25%;width:50%;height:50%;pointer-events:none;" class="aymatic_play_icon" 
				src="../modules/aymaticprestashop/css/images/baseline-play_circle_outline-24px.svg"/>`);
				$('#big_play_icon')[0].style.display = "none";
			}
		});

		/*$('#thumb_video_aymatic').mouseenter(function(){
			var bigpichtml = $('#bigpic')[0].outerHTML;
			var newBigPicWrapperString = `<div id="bigpicwrapper" class="youtube-video videocenter">` + bigpichtml + `<img 
			style="background:none;position:absolute;top:25%;right:25%;width:50%;height:50%" class="aymatic_play_icon" 
			src="../modules/aymaticprestashop/css/images/baseline-play_circle_outline-24px.svg"/></div>`;
			//$('#bigpic').replaceWith(newBigPicWrapperString);
			//$('#bigpic').wrap("<div id=\"bigpicwrapper\" class=\"youtube-video videocenter\"></div>");
			if(!$('#big_play_icon').length){
				$('#bigpic').after( `<img id="big_play_icon" style="background:none;position:absolute;top:25%;right:25%;width:50%;height:50%" class="aymatic_play_icon" 
				src="../modules/aymaticprestashop/css/images/baseline-play_circle_outline-24px.svg"/>`);
			}
			else{
				$('#big_play_icon')[0].style.display = "inline";
			}
		});
		$('#thumb_video_aymatic').mouseenter(function(){
			$('#big_play_icon')[0].style.display = "none";
			//var bigpichtml = $('#bigpic')[0].outerHTML;
			//$('#bigpicwrapper').replaceWith(bigpichtml);
		});*/


		//$('#thumb_video_aymatic').mouseenter(function(){
			
		//});
		/*$('#thumb_video_aymatic').mouseleave(function(){
			$('#big_play_icon')[0].style.display = "none";
		});*/

		/*$('#thumb_video_aymatic').mouseenter(function(){
			$('#big_play_icon')[0].style.display = "inline";
		});

		$('#thumb_video_aymatic').mouseleave(function(){
			if($('#thumb_video_aymatic').hasClass('shown')){
				$('#big_play_icon')[0].style.display = "inline";
			}
			else{
				$('#big_play_icon')[0].style.display = "none";
			}
			//$('#big_play_icon')[0].style.display = "none";
			//var bigpichtml = $('#bigpic')[0].outerHTML;
			//$('#bigpicwrapper').replaceWith(bigpichtml);
		});


		$('#thumbs_list_frame').mouseenter(function(){
			if(!$('#thumb_video_aymatic').hasClass('shown')){
				$('#big_play_icon')[0].style.display = "none";
			}
			//$('#big_play_icon')[0].style.display = "none";
			//var bigpichtml = $('#bigpic')[0].outerHTML;
			//$('#bigpicwrapper').replaceWith(bigpichtml);
		});

		$('#thumbs_list_frame').mouseleave(function(){
			if($('#thumb_video_aymatic').hasClass('shown')){
				$('#big_play_icon')[0].style.display = "inline";
			}
			//$('#big_play_icon')[0].style.display = "none";
			//var bigpichtml = $('#bigpic')[0].outerHTML;
			//$('#bigpicwrapper').replaceWith(bigpichtml);
		});*/

		

		/*$(document).ready(function(){
			if($('.fancybox').hasClass('shown')){
				$('.fancybox').removeClass('shown');
				$('.videocenter').addClass('shown');
				var $video_image = $("#thumb_image");
				var src = $video_image.attr("src");

				

				var bigpichtml = $('#bigpic')[0].outerHTML;
				var newBigPicWrapperString = `<div id="bigpicwrapper" class="youtube-video videocenter">` + bigpichtml + `<img 
				style="background:none;position:absolute;top:25%;right:25%;width:50%;height:50%" class="aymatic_play_icon" 
				src="../modules/aymaticprestashop/css/images/baseline-play_circle_outline-24px.svg"/></div>`;
				$('#bigpic').replaceWith(newBigPicWrapperString);
				
				$('#bigpic').attr('src',src);
			}else{
				$('.fancybox').addClass('shown');
				$('.videocenter').removeClass('shown');
			}
			$('#views_block').removeClass('hidden');
		});*/
	</script>
{/if}
