<script type="text/javascript" src="{$module_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{$module_path}js/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="{$module_path}css/jquery.fancybox.min.css" type="text/css" />
{literal}
<style type="text/css">
	#videoholder{display: none;}
	.fancybox-wrap{opacity: 1 !important;}
</style>
{/literal}
{if $status_video && $is_module_enable}
  <script type="text/javascript">
  	$('.product-images li:eq(0)').before('<li id="thumb_video" class="video_link"><a href="{$video_url}" class="youtube-video videocenter"><img style="width: 100px;height: 100px;" id="thumb_image" class="img-responsive video-thumb thumb" src="{$video_thumbnialurl}"></a></li>');
  	$( 'a.youtube-video' ).fancybox({
	  type: 'iframe'
	});
	if($('.js-thumb').hasClass('selected')){
		$('.js-thumb').removeClass('selected');
		$('.video-thumb').addClass('selected');
		var $video_image = $("#thumb_image");
		var src = $video_image.attr("src");
		$('.js-qv-product-cover').attr('src',src);
		//$('.product-cover-modal').attr('src',src);
		
	}else{
		$('.js-thumb').addClass('selected');
		$('.video-thumb').removeClass('selected');
	}
</script>

{/if}
