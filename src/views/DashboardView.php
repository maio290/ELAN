<?php


namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class DashboardView
{

    static function generate($SESSION)
    {
        $date = date("d.m.Y");
        $dashboardView = "";
        $user = User::deserialize($SESSION['user']);
        $ausbilder = AusbilderController::getAusbilderStateForUser($user->username);

        $metricsView = '<h2>Statistik</h2><div class="dashboardContainer">Willkommen bei ELAN - ' . $date;

        $metricsView .= MetricsController::getMetricsForUser($SESSION);
        $dashboardView .= $metricsView;
        $dashboardView .= '</div>';
        if ($ausbilder !== 0) {
            $ausbildungsverhaeltnisse = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForAusbilder($user->username);
            $dashboardView .= AusbilderDashboardView::generate($ausbildungsverhaeltnisse);
        } else {
            $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
            if($ausbildungsverhaeltnis)
            {
                $ausbildungsnachweise = AusbildungsnachweisController::getAusbildungsnachweiseByAVID($ausbildungsverhaeltnis->id);
                if ($ausbildungsnachweise !== null) {
                    $dashboardView .= '<h2>Ausbildungsnachweise</h2><div class="dashboardContainer">';
                    $dashboardView .= AusbildungsnachweisFormView::generate($ausbildungsnachweise, $ausbildungsverhaeltnis) . '<br>';
                }
                $dashboardView .= "</div><h2>Externer Zugriff</h2>";
                $dashboardView .= '<img src="generateQR.php?key='.$ausbildungsverhaeltnis->key.'"/>';
            }

        }

        return $dashboardView;

    }

}