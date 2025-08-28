<?php

set_time_limit(900);

// Support `?cmd=...` style GET requests
if (isset($_GET["cmd"])) {
    $_POST = $_GET;
}

include('../init.php');
include('../func/fn_reports.gen.php');

checkUserSession();
loadLanguage($_SESSION["language"], $_SESSION["units"]);

// Get user ID based on privileges
$user_id = ($_SESSION["privileges"] === 'subuser') ? $_SESSION["manager_id"] : $_SESSION["user_id"];

// Get filter inputs
$imeis = isset($_GET['imeis']) ? explode(',', $_GET['imeis']) : [];
$from_date = isset($_GET['from']) ? $_GET['from'] : date("Y-m-d 00:00:00");
$to_date = isset($_GET['to']) ? $_GET['to'] : date("Y-m-d H:i:s");
$rfid_filter = isset($_GET['rfid']) ? trim($_GET['rfid']) : '';

// Validate and sanitize IMEI input
if (!is_array($imeis)) {
    $imeis = [];
}

$imeis = array_filter($imeis, function ($val) {
    return trim($val) !== ''; // Remove empty strings, whitespace-only entries
});

// Use default IMEIs if none were provided
if (empty($imeis)) {
    if ($_SESSION["privileges"] === 'subuser') {
        $imeistr = $_SESSION["privileges_imei"] ?? '';
        $imeis = array_filter(explode(',', $imeistr), function ($val) {
            return trim($val) !== '';
        });
    } else {
        $imeistr = getUserObjectIMEIs($user_id);
        $imeis = array_filter(explode(',', $imeistr), function ($val) {
            return trim($val) !== '';
        });
    }
}
function reportsGenerateConsolidatedRfidDataLive($imeis, $dtf, $dtt, $rfid_filter = '', $show_coordinates = true, $show_addresses = true, $zones_addresses = true): string
{
    global $ms, $_SESSION, $la, $user_id, $gsValues;

    $user_id = ($_SESSION["privileges"] == 'subuser') ? $_SESSION["manager_id"] : $_SESSION["user_id"];

    if (!is_array($imeis) || count($imeis) === 0) {
        return '<table><tr><td>No IMEIs provided.</td></tr></table>';
    }

    $escaped_imeis = array_map(function ($imei) use ($ms) {
        $clean = trim($imei, '"\''); // remove " or ' from both ends
        return "'" . mysqli_real_escape_string($ms, $clean) . "'";
    }, $imeis);
    $imei_list = implode(',', $escaped_imeis);

    // echo $imei_list; 
    // die;

    $where = "gr.imei IN ($imei_list) AND gr.dt_swipe BETWEEN '$dtf' AND '$dtt'";
    if (!empty($rfid_filter)) {
        $rfid_safe = mysqli_real_escape_string($ms, $rfid_filter);
        $where .= " AND gr.rfid = '$rfid_safe'";
    }

    $q = "
        SELECT 
            gr.dt_swipe,
            gr.lat, gr.lng, gr.rfid,
            go.name AS vehicle_no
        FROM gs_rfid_swipe_data gr
        LEFT JOIN gs_objects go ON go.imei = gr.imei
        WHERE $where
        ORDER BY gr.dt_swipe ASC
    ";

    $r = mysqli_query($ms, $q);
    $count = mysqli_num_rows($r);

    $myfile = fopen("reportquery.txt", "a");
    fwrite($myfile,$q);
    fwrite($myfile, "\n");
    fclose($myfile);
    // die;
    if ($count == 0) {
        return json_encode([]); // Return empty JSON array if no records found
        // return '<table><tr><td>' . $la['NOTHING_HAS_BEEN_FOUND_ON_YOUR_REQUEST'] . '</td></tr></table>';
    }

    $date_map = [];

    while ($row = mysqli_fetch_assoc($r)) {
        $dt_swipe_local = convUserTimezone($row['dt_swipe']);

        $swipe_date = date('Y-m-d', strtotime($dt_swipe_local));
        $swipe_time = date('H:i:s', strtotime($dt_swipe_local));
        $key = $row['rfid'] . '_' . $row['vehicle_no'];

        if (!isset($date_map[$swipe_date])) {
            $date_map[$swipe_date] = [];
        }

        if (!isset($date_map[$swipe_date][$key])) {
            $date_map[$swipe_date][$key] = [
                'vehicle_no' => $row['vehicle_no'],
                'rfid' => $row['rfid'],
                'date' => $swipe_date,
                'swipes' => []
            ];
        }

        if (count($date_map[$swipe_date][$key]['swipes']) < 4) {
            $date_map[$swipe_date][$key]['swipes'][] = [
                'time' => $swipe_time,
                'loc' => reportsGetPossition($row['lat'], $row['lng'], $show_coordinates, $show_addresses, $zones_addresses)
            ];
        }
    }
    return json_encode($date_map);

    // Generate HTML per date
    // $result = '';
    // foreach ($date_map as $date => $entries) {
    //     $result .= '<h3 style="margin-top:30px;">Date: ' . htmlspecialchars($date) . '</h3>';
    //     $result .= '<table class="report" width="100%" border="1" cellpadding="5" cellspacing="0">
    //         <tr align="center" style="background-color:#f2f2f2;">
    //             <th>SN</th>
    //             <th>Vehicle No</th>
    //             <th>RFID Tag</th>
    //             <th>Date</th>';

    //     for ($i = 1; $i <= 4; $i++) {
    //         $result .= "<th>Swipe $i</th><th>Loc$i</th>";
    //     }
    //     $result .= '</tr>';

    //     $sn = 1;
    //     foreach ($entries as $entry) {
    //         $result .= '<tr align="center">';
    //         $result .= '<td>' . $sn++ . '</td>';
    //         $result .= '<td>' . htmlspecialchars($entry['vehicle_no']) . '</td>';
    //         $result .= '<td>' . htmlspecialchars($entry['rfid']) . '</td>';
    //         $result .= '<td>' . htmlspecialchars($entry['date']) . '</td>';

    //         for ($i = 0; $i < 4; $i++) {
    //             if (isset($entry['swipes'][$i])) {
    //                 $result .= '<td>' . htmlspecialchars($entry['swipes'][$i]['time']) . '</td>';
    //                 $result .= '<td>' . $entry['swipes'][$i]['loc'] . '</td>';
    //             } else {
    //                 $result .= '<td></td><td></td>';
    //             }
    //         }
    //         $result .= '</tr>';
    //     }

    //     $result .= '</table>';
    // }

    // return $result;
}

// Call function using filters
$report = reportsGenerateConsolidatedRfidDataLive($imeis, convUserUTCTimezone($from_date), convUserUTCTimezone($to_date), $rfid_filter, true, true, true);
echo $report;
?>