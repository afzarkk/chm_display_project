<?php

date_default_timezone_set("Asia/Kolkata");

$host = "";
$dbname = "scheduler";
$username = "";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$query = "
    SELECT 
        a.room_name, 
        s.subject, 
        s.staff_name, 
        s.datetime_start, 
        s.datetime_end 
    FROM 
        schedule_list s
    JOIN 
        assembly_hall a ON s.assembly_hall_id = a.id
    WHERE 
        DATE(datetime_start) = CURDATE()
        AND s.assembly_hall_id = 2
    ORDER BY 
        datetime_start";

$stmt = $pdo->prepare($query);
$stmt->execute();
$meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($meetings);
$currentTime = new DateTime();
$nextMeeting = null;
$pastMeetings = [];
$currentMeeting = null;

//         PAGE REDIRECTION

$currentTime = new DateTime(); // Current time

$pageToRedirect = "index.php"; // Default redirection

foreach ($meetings as $meeting) {
    $start = new DateTime($meeting['datetime_start']);
    $end = new DateTime($meeting['datetime_end']);

    if ($currentTime >= $start && $currentTime <= $end) {
        $currentMeeting = $meeting; // Current meeting in progress
    } elseif ($currentTime < $start) {
        if (!$nextMeeting) {
            $nextMeeting = $meeting; // Next upcoming meeting
        }
    } else {
        $pastMeetings[] = $meeting; // Past meetings
    }

    
    if ($meeting) {
        $datetimeStart = new DateTime($meeting["datetime_start"]);
        $datetimeEnd = new DateTime($meeting["datetime_end"]);

        // Calculate time differences in seconds
        $timeDiffToStart =
            $datetimeStart->getTimestamp() - $currentTime->getTimestamp();
        $timeDiffToEnd =
            $datetimeEnd->getTimestamp() - $currentTime->getTimestamp();


        if ($timeDiffToStart > 1200) {
            // More than 20 minutes to start: stay on index.php
            $pageToRedirect = "index1.php";
        } elseif ($timeDiffToStart <= 1200 && $timeDiffToStart > 0) {
            // Within 20 minutes of starting: redirect to starting.php
            $pageToRedirect = "starting.php";
        } elseif ($timeDiffToStart <= 0 && $timeDiffToEnd > 0) {
            // var_dump($timeDiffToStart);
            // Meeting in progress: redirect to inuse.php
            $pageToRedirect = "inuse.php";
        } else {
            // Meeting has ended: redirect to index.php
            $pageToRedirect = "index1.php";
        }
    } else {
        // No meetings scheduled for today: stay on index.php
        $pageToRedirect = "index1.php";
    }
}

// Default display values
$assemblyHall = $nextMeeting["room_name"] ?? "N/A";
$eventName = $nextMeeting["subject"] ?? "No Event";
$staffName = $nextMeeting["staff_name"] ?? "Unknown";
$datetimeStart = $nextMeeting["datetime_start"] ?? null;
$datetimeEnd = $nextMeeting["datetime_end"] ?? null;
$formattedTime = $datetimeStart
    ? (new DateTime($datetimeStart))->format("g:i A") . " - " . (new DateTime($datetimeEnd))->format("g:i A")
    : "N/A";

function calculateGridRow($datetime) {
    $dt = new DateTime($datetime);
    $hour = (int)$dt->format('H');
    $minute = (int)$dt->format('i');
    return (($hour - 9) * 60) + $minute + 1;
}


// Output the redirection page for JavaScript handling
// echo json_encode(['redirect' => $pageToRedirect]);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHM Display</title>
    <link rel="icon" href="fevi.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css'>
    <link rel='stylesheet' href='style.css'>
    <style>
        .date {
            border-bottom: 2px solid #509f6b;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 green_bg">
                <div class="dis_right card-height-100">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="logo pt-4 pb-5">
                                <img src="zigma-logo.png" width="80px">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="logo pt-4 pb-5 pe-3">
                                <img src="blue-planet-logo.png" width="155px">
                            </div>
                        </div>
                    </div>
                    <div class="meeting_content">
                        <div class="meeting_details">
                            <h3><?= htmlspecialchars($assemblyHall) ?></h3>
                            <?php if ($nextMeeting): ?>
                                <h1 class="mt-4 mb-4">Available</h1>
                                <h4 class="mb-3">
                                    <i class="bi-card-heading"></i> &nbsp; <?= htmlspecialchars($nextMeeting["subject"]) ?>
                                </h4>
                                <h4 class="mb-3">
                                    <i class="bi-person-badge"></i> &nbsp; <?= htmlspecialchars($nextMeeting["staff_name"]) ?>
                                </h4>
                                <h4 class="mb-3">
                                    <i class="bi-alarm"></i> &nbsp; Timing: <?= $formattedTime ?>
                                </h4>
                            <?php else: ?>
                                <h1 class="mt-4 mb-4">No Meetings</h1>
                            <?php endif; ?>
                        </div>
                        <div class="timing_details mt-5 pt-2 text-end pe-3">
                            <h3><?= date("l, F j, Y") ?></h3>
                            <h1><?= date("h:ia") ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 greendark_bg">
                <div class="dis_left">
                    <div class="calendar">
                        <div class="timeline">
                            <div class="spacer"></div>
                            <?php for ($i = 9; $i <= 24; $i++): ?>
                                <div class="time-marker"><?= $i % 12 ?: 12 ?> <?= $i >= 12 ? 'PM' : 'AM' ?></div>
                            <?php endfor; ?>
                        </div>
                        <div class="days">
                            <div class="day mon">
                                <div class="date">
                                    <p class="date-num">Today</p>
                                    <p class="date-day">Meeting Schedule</p>
                                </div>
                                <div class="events">
                                    

                                    <?php if ($currentMeeting): ?>
                                        <div class="event" style="grid-row-start: <?= calculateGridRow($currentMeeting["datetime_start"]) ?>; grid-row-end: <?= calculateGridRow($currentMeeting["datetime_end"]) ?>;">
                                            <p class="title"><?= htmlspecialchars($currentMeeting["subject"]) ?> (In Progress)</p>
                                            <p class="time"><?= (new DateTime($currentMeeting["datetime_start"]))->format("g:i A") ?> - <?= (new DateTime($currentMeeting["datetime_end"]))->format("g:i A") ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php foreach ($meetings as $meeting): ?>
                                        <?php if ($currentTime < new DateTime($meeting["datetime_start"])): ?>
                                            <div class="event" style="grid-row-start: <?= calculateGridRow($meeting["datetime_start"]) ?>; grid-row-end: <?= calculateGridRow($meeting["datetime_end"]) ?>;">
                                                <p class="title"><?= htmlspecialchars($meeting["subject"]) ?></p>
                                                <p class="time"><?= (new DateTime($meeting["datetime_start"]))->format("g:i A") ?> - <?= (new DateTime($meeting["datetime_end"]))->format("g:i A") ?></p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Function to check server for the current redirection page
        async function checkRedirect() {
            try {
                console.log('function called')
                const response = await fetch('scheduler_logic.php'); 
                const data = await response.json();
                console.log(data)

                if (window.location.pathname !== `/${data.redirect}`) {
                    window.location.href = data.redirect;
                }
            } catch (error) {
                console.error('Error checking redirection:', error);
            }
        }

        // Check redirection every 10 seconds
        setInterval(checkRedirect, 10000);
    </script>
</body>
</html>
