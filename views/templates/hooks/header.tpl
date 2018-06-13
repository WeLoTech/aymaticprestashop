
{if $app_id}
    <meta property="fb:app_id " content="{$app_id}"/>
{/if}
{if $contact_email}
    <meta property="og:email" content="{$contact_email}"/>
{/if}
{if $admin_id}
    <meta property="fb:admins" content="{$admin_id}"/>
{/if}
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/{$lang}/sdk.js#xfbml=1&version=v2.12&appId={$app_id}&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>