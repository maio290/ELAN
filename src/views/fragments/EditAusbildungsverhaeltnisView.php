<?php

namespace ELAN;
class EditAusbildungsverhaeltnisView
{
    static function generate($ausbildungsverhaeltnisse)
    {
        return TableGenerator::generateTableForObjects($ausbildungsverhaeltnisse, "editAusbildungsverhaeltnis", true);
    }
}
