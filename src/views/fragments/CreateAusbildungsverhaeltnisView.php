<?php

namespace ELAN;

class CreateAusbildungsverhaeltnisView
{

    static function generate($userSelectOptions)
    {
        return
            '<div class="createAusbildungsverhaeltnis" hidden>
            <form action="src/actions/create.php" method="POST">
            Ausbilder: 
            <select name="ausbilder" required>
            ' . $userSelectOptions . '
            </select><br>
            Auszubildender:
            <select name="azubi" required>
            ' . $userSelectOptions . '
            </select><br>
            Start der Ausbildung: <input type="date" name="start" required><br>
            Ende der Ausbildung: <input type="date" name="end" required><br>
            Beruf: <input type="text" name="beruf" required></br>
            <input type="submit" value="Ausbildungsverhältnis erstellen"/>
            </form>
            </div>';
    }

}