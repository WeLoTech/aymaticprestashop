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
	</script>
{/if}
