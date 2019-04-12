<?php


namespace ELAN;


class AzubiMetrics
{

    public $geschrieben;
    public $freigegeben;
    public $korrektur;
    public $toWrite;
    public $written;
    public $end;

    function __construct($geschrieben, $freigegeben, $korrektur, $toWrite, $written, $end)
    {
        $this->geschrieben = $geschrieben;
        $this->freigegeben = $freigegeben;
        $this->korrektur = $korrektur;
        $this->toWrite = $toWrite;
        $this->written = $written;
        $this->end = $end;
    }


}