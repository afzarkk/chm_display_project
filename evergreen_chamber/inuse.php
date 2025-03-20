<?php
// Set the timezone
date_default_timezone_set('Asia/Kolkata'); 

$host     = '192.168.1.22'; 
$dbname   = 'scheduler'; 
$username = 'my_root'; 
$password = 'my@123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch the schedule data for the current date
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
$assemblyHall = $currentMeeting["room_name"] ?? "N/A";
$eventName = $currentMeeting["subject"] ?? "No Event";
$staffName = $currentMeeting["staff_name"] ?? "Unknown";
$datetimeStart = $currentMeeting["datetime_start"] ?? null;
$datetimeEnd = $currentMeeting["datetime_end"] ?? null;
$formattedTime = $datetimeStart
    ? (new DateTime($datetimeStart))->format("g:i A") . " - " . (new DateTime($datetimeEnd))->format("g:i A")
    : "N/A";

function calculateGridRow($datetime) {
    $dt = new DateTime($datetime);
    $hour = (int)$dt->format('H');
    $minute = (int)$dt->format('i');
    return (($hour - 9) * 60) + $minute + 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CHM Display</title>
	 <link rel="icon" href="fevi.png" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
		
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.2.2/font/bootstrap-icons.css'>
	  <link rel='stylesheet' href='style.css'>
</head>
<style>
    .date {
    border-bottom: 2px solid #bd646a;
}
</style>

<body>
   
   <div class="container-fluid">
   <div class="row">
	<div class="col-md-8 red_bg">
		<div class="dis_right card-height-100">
		<div class="row">
		        <div class="col-md-6">
		    	    <div class="logo pt-4 pb-5"><img src="zigma-logo.png" width="80px;"></div>
		    	</div>
		    	<div class="col-md-6 text-end">
		    	     <div class="logo pt-4 pb-5 pe-3"><img src="blue-planet-logo.png" width="155px;"></div>
		    	</div>
			</div>
            <div class="meeting_content">
                        <div class="meeting_details">
                            <h1>Evergreen Chamber</h1>
                            <?php if ($currentMeeting): ?>
                                <h1 class="mt-4 mb-4">In Use</h1>
                                <h4 class="mb-3">
                                    <i class="bi-card-heading"></i> &nbsp; <?= htmlspecialchars($currentMeeting["subject"]) ?>
                                </h4>
                                <h4 class="mb-3">
                                    <i class="bi-person-badge"></i> &nbsp; <?= htmlspecialchars($currentMeeting["staff_name"]) ?>
                                </h4>
                                <h4 class="mb-3">
                                    <i class="bi-alarm"></i> &nbsp; Timing: <?= (new DateTime($currentMeeting["datetime_start"]))->format("g:i A") ?> - <?= (new DateTime($currentMeeting["datetime_end"]))->format("g:i A") ?>
                                </h4>
                            <?php else: ?>
                                <h1 class="mt-4 mb-4">In Use</h1>
                            <?php endif; ?>
                        </div>
                        <!-- <div class="timing_details mt-5 pt-2 text-end pe-3">
                            <h3><?= date("l, F j, Y") ?></h3>
                            <h1><?= date("h:ia") ?></h1>
                        </div> -->
                        <!-- <div class="timing_details mt-5 pt-2 text-end pe-3">
                          <h3 id="current-date"><?= date('l, F j, Y') ?></h3>
                          <h1 id="current-time"><?= date('h:i:s a') ?></h1>
                        </div> -->
                        <div class="timing_details mt-5 pt-2 text-end pe-3">
                            <h3 id="current-date"><?= date('l, F j, Y') ?></h3>
                            <h1 id="current-time"><?= date('H:i:s') ?></h1>
                        </div>

                    </div>
		</div>

   </div>
   
    
   <div class="col-md-4 reddark_bg">
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

                        <?php foreach ($pastMeetings as $meeting): ?>
                                        <div class="event red_event" style="grid-row-start: <?= calculateGridRow($meeting["datetime_start"]) ?>; grid-row-end: <?= calculateGridRow($meeting["datetime_end"]) ?>;">
                                            <p class="title"><?= htmlspecialchars($meeting["subject"]) ?> </p>
                                            <p class="time"><?= (new DateTime($meeting["datetime_start"]))->format("g:i A") ?> - <?= (new DateTime($meeting["datetime_end"]))->format("g:i A") ?></p>
                                        </div>
                                    <?php endforeach; ?>


                            <?php if ($currentMeeting): ?>
                                <div class="event red_event" style="grid-row-start: <?= calculateGridRow($currentMeeting["datetime_start"]) ?>; grid-row-end: <?= calculateGridRow($currentMeeting["datetime_end"]) ?>;">
                                    <p class="title"><?= htmlspecialchars($currentMeeting["subject"]) ?></p>
                                    <p class="time"><?= (new DateTime($currentMeeting["datetime_start"]))->format("g:i A") ?> - <?= (new DateTime($currentMeeting["datetime_end"]))->format("g:i A") ?></p>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($meetings as $meeting): ?>
                                <?php if ($currentTime < new DateTime($meeting["datetime_start"])): ?>
                                    <div class="event red_event" style="grid-row-start: <?= calculateGridRow($meeting["datetime_start"]) ?>; grid-row-end: <?= calculateGridRow($meeting["datetime_end"]) ?>;">
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
 
</body>
</html>

<script>
    // Function to check server for the current redirection page
    async function checkRedirect() {
        try {
            const response = await fetch('scheduler_logic.php'); // Replace with the PHP file URL
            const data = await response.json();

            // If the current URL does not match the redirection page, redirect
            if (window.location.pathname !== `/${data.redirect}`) {
                window.location.href = data.redirect;
            }
        } catch (error) {
            console.error('Error checking redirection:', error);
        }
    }

    // Check redirection every 10 seconds
    setInterval(checkRedirect, 10000);

    // Initial check on page load
    // checkRedirect();


    // function updateTime() {
    //     const timeElement = document.getElementById('current-time');
    //     const date = new Date();

    //     // Format time as hh:mm:ss am/pm
    //     let hours = date.getHours();
    //     let minutes = date.getMinutes();
    //     let seconds = date.getSeconds();
    //     const ampm = hours >= 12 ? 'pm' : 'am';
    //     hours = hours % 12 || 12; // Convert to 12-hour format
    //     minutes = minutes.toString().padStart(2, '0');
    //     seconds = seconds.toString().padStart(2, '0');

    //     timeElement.textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
    // }

    // // Update the time every second
    // setInterval(updateTime, 1000);

    function updateTime() {
    const timeElement = document.getElementById('current-time');
    const date = new Date();

    // Format time as HH:mm:ss (24-hour format)
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let seconds = date.getSeconds();

    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    seconds = seconds.toString().padStart(2, '0');

    timeElement.textContent = `${hours}:${minutes}:${seconds}`;
}

// Update time every second
setInterval(updateTime, 1000);

// Initialize time on page load
updateTime();



</script>
