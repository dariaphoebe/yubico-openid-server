<?php

require_once "lib/session.php";
require_once "lib/render.php";

define('trust_form_pat',
       '<div class="form">
  <form name="login" method="post" action="%s">
%s
    <input autocomplete="off" type="password" name="yubikey" id="yubikey" />
    <input type="submit" name="trust" value="Confirm" />
    <input type="hidden" name="trust"/>
  </form>
  <form method="post" action="%s">
    <input type="submit" value="Do not confirm" />
  </form>
</div>
');

define('normal_pat',
       '<p>Confirm <!-- %s --> login to:</p>' .
       '<p><b>%s</b></p>' .
       '<p>by pressing button on your YubiKey.</p>');

define('id_select_pat',
       '<p>You entered the server URL at the RP.
Confirm your login by pressing button on your YubiKey.<br/>
<input type="hidden" name="idSelect" /></p>
');

define('no_id_pat',
'
You did not send an identifier with the request,
and it was not an identifier selection request.
Please return to the relying party and try again.
');

function trust_render($info)
{
    $current_user = getLoggedInUser();
    $lnk = link_render(idURL($current_user));
    $trust_root = htmlspecialchars($info->trust_root);
    $trust_url = buildURL('trust', true);

    if ($info->idSelect()) {
        $prompt = id_select_pat;
    } else {
        $prompt = sprintf(normal_pat, $lnk, $trust_root);
    }

    $form = sprintf(trust_form_pat, $trust_url, $prompt, $trust_url);

    return page_render($form, $current_user, 'Trust This Site');
}

function noIdentifier_render()
{
    return page_render(no_id_pat, null, 'No Identifier Sent');
}

?>
