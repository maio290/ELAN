<?php


namespace ELAN;


class MetricsView
{

    static function generate(AzubiMetrics $metrics)
    {
        return '
        <div>• Das voraussichtliche Ende deiner Ausbildung ist am: ' . TimeUtils::epochToGermanDate(strtotime($metrics->end)) . '</div>
        <div>• <a href="view.php?queue=written">Es sind ' . $metrics->geschrieben . ' Ausbildungsnachweise noch nicht freigegeben</a></div>
        <div>• <a href="view.php?queue=faulty">Es sind ' . $metrics->korrektur . ' Nachweise zur Korrektur vorhanden</a></div>
        <div>• <a href="view.php?queue=new">Du musst noch ' . $metrics->toWrite . ' Nachweise schreiben</a></div>
        <div>• Du hast bereits ' . $metrics->written . ' Nachweise geschrieben</div>
        ';
    }

    static function generateAusbilderView($metrics)
    {
        return '
        <div>• Dir sind ' . $metrics->azubis . ' Auszubildende zugewiesen</div>
        <div>• Du hast bereits ' . $metrics->signiert . ' Nachweise signiert</div>
        <div>• <a href="view.php?queue=sign"> Es sind ' . $metrics->freigegeben . ' Nachweise zum Signieren vorhanden</a></div>
        <div>• Es warten noch ' . $metrics->korrektur . ' Nachweise auf eine Korrektur durch den Azubi</div>
        <div>• Es wird noch ' . $metrics->ausstehend . ' Nachweise geben</div>
        ';
    }

}