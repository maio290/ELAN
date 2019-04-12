<?php


namespace ELAN;


class AusbilderMetrics
{

    public $azubis;
    public $ausstehend;
    public $freigegeben;
    public $korrektur;
    public $signiert;

    function __construct($azubis, $ausstehend, $freigegeben, $korrektur, $signiert)
    {
        $this->azubis = $azubis;
        $this->ausstehend = $ausstehend;
        $this->freigegeben = $freigegeben;
        $this->korrektur = $korrektur;
        $this->signiert = $signiert;
    }


}