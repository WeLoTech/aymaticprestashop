{literal}
<style type="text/css">
	#videoholder{display: none;}
	.fancybox-wrap{opacity: 1 !important;}
</style>
{/literal}
{if $status_video && $is_module_enable}
	<script type="text/javascript">
		$('#thumbs_list_frame li:eq(0)').after('<li id="thumb_video_aymatic"  class="aymatic_video_link"><a href="{$video_thumbnialurl}" data-video="{$video_url}" class="youtube-video videocenter"><img id="thumb_image" class="img-responsive video-thumb" src="{$video_thumbnialurl}"></a></li>');

		$("a.youtube-video").on("click", function(){
			$.fancybox({
				href: $(this).data("video"),
				type: 'iframe'
			}); // fancybox
			return false;
		}); // on

		$(document).ready(function(){
			if($('.fancybox').hasClass('shown')){
				$('.fancybox').removeClass('shown');
				$('.videocenter').addClass('shown');
				var $video_image = $("#thumb_image");
				var src = $video_image.attr("src");
				$('#bigpic').attr('src',src);
			}else{
				$('.fancybox').addClass('shown');
				$('.videocenter').removeClass('shown');
			}
			$('#views_block').removeClass('hidden');
		});
	</script>
{/if}
