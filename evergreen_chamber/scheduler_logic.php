<?php
// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Database credentials
$host     = '192.168.1.22';
$dbname   = 'scheduler';
$username = 'my_root';
$password = 'my@123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
}

// Query to fetch the next meeting for today in the specified assembly hall
$query = "
    SELECT 
        s.datetime_start, 
        s.datetime_end 
    FROM 
        schedule_list s
    WHERE 
        DATE(s.datetime_start) = CURDATE()
        AND s.assembly_hall_id = 2
    ORDER BY 
        s.datetime_start ";

$stmt = $pdo->prepare($query);
$stmt->execute();
$meeting_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentTime = new DateTime(); // Current time
$pageToRedirect = 'index1.php'; // Default redirection

foreach($meeting_data as $data){
    if ($data) {
        $datetimeStart = new DateTime($data['datetime_start']);
        $datetimeEnd = new DateTime($data['datetime_end']);
    
        // Calculate time differences in seconds
        $timeDiffToStart = $datetimeStart->getTimestamp() - $currentTime->getTimestamp(); 
        $timeDiffToEnd = $datetimeEnd->getTimestamp() - $currentTime->getTimestamp(); 
    
        if ($timeDiffToStart > 1200) {
            $pageToRedirect = 'index1.php'; // More than 20 minutes to start
            continue;
        } elseif ($timeDiffToStart <= 1200 && $timeDiffToStart > 0) {
            $pageToRedirect = 'starting.php'; // Within 20 minutes to start
            break;
        } elseif ($timeDiffToStart <= 0 && $timeDiffToEnd > 0) {
            $pageToRedirect = 'inuse.php'; // Meeting in progress
            break;
        } else {
            $pageToRedirect = 'index1.php'; // Meeting has ended
            continue;
        }       
    }
    
}
 // Output the redirection page as JSON
echo json_encode(['redirect' => $pageToRedirect]);
?>
