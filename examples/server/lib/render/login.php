<?php

require_once "lib/session.php";
require_once "lib/render.php";

define('login_form_pat',
       '<div class="form">
  <p>
    <!-- Enter your Yubikey into this form to log in to this server. -->
    <!-- %s -->
  </p>
  <form name="login" method="post" action="%s">
    <table>
      <tr>
        <td>YubiKey:</td>
        <td>
           <input class="yubiKeyInput" type="text" name="yubikey" id="yubikey" style="width:230px;"/>
        </td>
        <td align=right>
           <input type="submit" value="Log in" style="width:60px;"/>
	</td>
      </tr>
    </table>
  </form>
</div>
');

define('login_needed_pat',
       'You must be logged in as %s to approve this request.');

function login_render($errors=null, $input=null, $needed=null)
{
    $current_user = getLoggedInUser();
    if ($input === null) {
        $input = $current_user;
    }
    if ($needed) {
        $errors[] = sprintf(login_needed_pat, link_render($needed));
    }

    $esc_input = htmlspecialchars($input, ENT_QUOTES);
    $login_url = buildURL('login', true);
    $body = sprintf(login_form_pat, idURL('USERNAME'), $login_url, $esc_input);
    if ($errors) {
        $body = loginError_render($errors) . $body;
    }
    return page_render($body, $current_user, 'Login to Yubico OpenID Server', null, true);
}

function loginError_render($errors)
{
    $text = '';
    foreach ($errors as $error) {
        $text .= sprintf("<li>%s</li>\n", $error);
    }
    return sprintf("<ul class=\"error\">\n%s</ul>\n", $text);
}
?>