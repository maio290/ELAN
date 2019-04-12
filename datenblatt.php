<?php
namespace ELAN;
require_once(__DIR__ . '/vendor/autoload.php');
if($_GET)
{
    if(isset($_GET['key']))
    {
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForKey($_GET['key']);
        $cfg = new Config_Provider();
        $ldap = new LDAP_Provider($cfg);
        $displayname = $ldap->getDisplayNameForCN($ausbildungsverhaeltnis->azubi);
        $externalURL = $cfg->host."/view.php?key=".$_GET['key'];

        echo '
<!DOCTYPE html>
<head>
    <title>ELAN - Datenblatt</title>
    <meta charset="UTF-8">
</head>
<body>
<h2>Elektronischer Ausbildungsnachweis - Datenblatt</h2>
Sehr geehrte Prüferin,<br>
sehr geehrte Prüfer,<br>

<p>der Prüfling '.$displayname.' führt seine Ausbildungsnachweise elektronisch.</p>

<p>Sie können unter folgendem Link auf die Ausbildungsnachweise zugreifen: <a href="'.$externalURL.' ">'.$externalURL.' </a></p>

Alternativ können Sie folgenden QR-Code scannen:</br>
<img src="generateQR.php?key='.$_GET['key'].'"</body>
';

    }
}
