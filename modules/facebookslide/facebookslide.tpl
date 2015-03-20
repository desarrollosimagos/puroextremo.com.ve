<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/{$lang_iso}_{$lang_iso|upper}/all.js#xfbml=1&appId=345551085518968";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="fblike-box" {if $fbmod_position==1}class="left_fb"{else}class="right_fb"{/if}>
	<div class="outside">
		<div class="inside">
			<div class="fb-like-box" data-href="{$fbmod_link}" data-width="292" data-show-faces="{if $fbmod_faces==1}true{else}false{/if}" data-stream="{if $fbmod_stream==1}true{else}false{/if}" data-show-border="false" data-header="false"></div>

		</div>
	</div>
	<div class="belt"><i class="icon-facebook"></i></div>
</div>