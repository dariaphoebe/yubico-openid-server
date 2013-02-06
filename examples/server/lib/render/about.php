<?php

require_once "lib/session.php";
require_once "lib/render.php";

define('about_error_template',
       '<div class="error">
An error occurred when processing your request:
<br />
%s
</div>');

define('about_body_short',
       '<p>
Please log in using your Yubikey.
</p>
<p>You can <a href="http://www.openid.net/">read more about OpenID</a>.
</p>');


define('about_body',
       '<p>
  Welcome!
<p>

  There are two ways to use the Yubico OpenID server. The simplest is
  to go to any site which supports OpenID and log in to it using the
  following OpenID URL:
</p>
<pre>
%s
</pre>
<p>
  You will then be redirected to our OpenID server, and can login to
  the site authenticated using your YubiKey.
</p>
<p>
  If you wish to use a more personal OpenID URL, you need to set up
  your own web site and be able to modify the HTML code for it.
  To use this server, you will have to set up a URL to use as an identifier.
  Insert the following markup into the <code>&lt;head&gt;</code> of the HTML
  document at your own home page:
</p>
<pre>&lt;link rel="openid.server" href="%s" /&gt;
&lt;link rel="openid.delegate" href="%s" /&gt;
</pre>
<p>
  Once you have made this change, you should be able to use your own URL
  as the OpenID URL when using any OpenID enabled site.
</p>
<p>
  You can verify that it is working by using the external
  <a href="http://www.openidenabled.com/resources/openid-test/checkup">OpenID
  Checkup tool</a>.
</p>
<p>
  You can <a href="http://www.openid.net/">read more about OpenID</a>.
</p>');
');

/**
 * Render the about page, potentially with an error message
 */
function about_render($error=false, $internal=true)
{
    $headers = array();
    $current_user = getLoggedInUser();
    if ($current_user) {
        $body = sprintf(about_body, idURL($current_user), buildURL(), idURL($current_user));
    } else {
        $body = sprintf(about_body_short);
    }
    if ($error) {
        $headers[] = $internal ? http_internal_error : http_bad_request;
        $body .= sprintf(about_error_template, htmlspecialchars($error));
    }
    return page_render($body, $current_user, 'Yubico OpenID Server');
}

?>
