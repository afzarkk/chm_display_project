<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="icon" href="fevi.png" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>CHM Display</title>
  
  
<style>
@import url("//fonts.googleapis.com/css?family=Lato:400,300,200,400italic,500,500italic");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  color: #fff;
  font-family: Lato, sans-serif;
  font-weight: 300;
}
.z {
  transform: translateZ(0);
  -webkit-transform: translateZ(0);
  -webkit-backface-visibility: hidden;
}
.cover {
  position: fixed;
  min-width: 100vw;
  min-height: 100vh;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
.left-bg {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 500px;
  min-height: 100vh;
  background-color: #020616;
  background: #001b38;
  background: -moz-linear-gradient(left, rgba(0,27,56,0.85) 0%, rgba(0,27,56,0.85) 5%, rgba(0,27,56,0) 100%);
  background: -webkit-linear-gradient(left, rgba(0,27,56,0.85) 0%, rgba(0,27,56,0.85) 5%, rgba(0,27,56,0) 100%);
  background: linear-gradient(to right, rgba(0,27,56,0.85) 0%, rgba(0,27,56,0.85) 5%, rgba(0,27,56,0) 100%);
}
.meetings {
  position: absolute;
  left: -25px;
  top: 36px;
}
.meetings.in .meeting {
  opacity: 0.75;
  transform: translateX(25px);
}
.meetings .meeting:hover {
  opacity: 0.85;
  transform: translateX(32px);
  cursor: pointer;
}
.meetings.in .meeting.current {
  transform: translateX(100px);
}
.meetings .meeting.current:hover {
  opacity: 0.9;
  transform: translateX(112px);
  cursor: pointer;
}
.meeting {
  background-color: #001b38;
  background-color: #001b38;
  width: 500px;
  height: 120px;
  margin-bottom: 20px;
  padding: 15px 25px 15px 50px;
  opacity: 0;
  transition: all 300ms ease-in-out;
}
.meeting h2 {
  font-weight: 300;
  font-size: 28.8px;
  margin-bottom: 4px;
  width: 420px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.meeting h3 {
  font-weight: 200;
  font-size: 22.4px;
  margin-bottom: 1px;
  letter-spacing: 0.5px;
  opacity: 0.85;
}
.meeting h4 {
  font-weight: 200;
  font-size: 16px;
  letter-spacing: 0.5px;
  opacity: 0.85;
}
.meeting.current {
  background-color: #13365b;
  height: 165px;
  width: 600px;
  padding: 20px 25px 20px 50px;
  margin-left: -75px;
}
.meeting.current h2 {
  font-size: 36px;
  margin-bottom: 10px;
  transition: all 300ms ease-in-out;
}
.meeting.current h2.available {
  color: #9ce051;
  font-weight: 400;
}
.meeting.current h3 {
  font-size: 28px;
  margin-bottom: 8px;
  transition: all 300ms ease-in-out;
}
.meeting.current h4 {
  font-size: 20px;
  transition: all 300ms ease-in-out;
}
.meeting.passed {
  opacity: 0.5 !important;
}
.meeting.passed h2,
.meeting.passed h3,
.meeting.passed h4 {
  color: #555 !important;
}
.meeting:nth-of-type(1) {
  transition-delay: 100ms;
}
.meeting:nth-of-type(2) {
  transition-delay: 150ms;
}
.meeting:nth-of-type(3) {
  transition-delay: 220ms;
}
.meeting:nth-of-type(4) {
  transition-delay: 280ms;
}
.meeting:nth-of-type(5),
.meeting:nth-of-type(6),
.meeting:nth-of-type(7),
.meeting:nth-of-type(8),
.meeting:nth-of-type(9) {
  transition-delay: 320ms;
}
.top.right {
  position: fixed;
  top: 36px;
  right: 36px;
  text-align: right;
}
.top.right h1 {
  font-size: 40px;
  font-weight: 500;
}
.top.right h2 {
  font-size: 32px;
}
.top.right h2.available {
  color: #9ce051;
}
.top.right h2.occupied {
  color: #e05151;
}
.bottom.right {
  position: fixed;
  bottom: 36px;
  right: 36px;
  text-align: right;
}
.bottom.right h1 {
  font-weight: 200;
  font-size: 24px;
}
.bottom.right h2 {
  font-weight: 300;
  font-size: 100px;
}
h2.available {
    color: #9ce051;
    font-weight: 800;
}
h2.occupied {
    color: #ff963b;
    font-weight: 800;
}
</style>

</head>

<body translate="no">
  <img class="bg cover z" src="https://images.unsplash.com/photo-1464621922360-27f3bf0eca75?h=768&amp;w=1024">

<div class="left-bg"></div>

<div class="meetings">

  <div class="meeting z">
    <h3>2:00pm – 2:30pm</h3>
    <h2>Conference room (Block A)</h2>
    <h2 class="available"> AVAILABLE</h2>
  </div>
  
  <!--<div style="display:nones" class="meeting z current"><h3>NOW</h3><h2 class="available">RESERVING ROOM</h2><h4>TAP TO CANCEL</h4></div>-->
  
  <div class="meeting z">
    <h3>2:00pm – 2:30pm</h3>
    <h2>Conference room (Block B)</h2>
     <h2 class="available"> AVAILABLE</h2>
  </div>
  
  <div class="meeting z">
    <h3>2:00pm – 2:30pm</h3>
    <h2>Conference room (VIP)</h2>
     <h2 class="occupied"> OCCUPIED</h2>
  </div>
  
  <div class="meeting z">
    <h3>2:00pm – 2:30pm</h3>
    <h2>Training Hall</h2>
    <h2 class="available"> AVAILABLE</h2>
  </div>
</div>

<div class="top right">
  <h1></h1>
  <h2></h2>
</div>

<div class="bottom right">
  <h1></h1>
  <h2></h2>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
      <script id="rendered-js" >
var occupied;
//var availableString = '<div class="meeting z current empty"><h3>NOW</h3><h2 class="available">ROOM AVAILABLE</h2><h4>TAP HERE TO RESERVE</h4></div>';
var backgroundImage = null,locationDisplayString = null;

function UP(n) {
  n = n || 500;
  $('html, body').animate({
    scrollTop: $('.meeting.current').offset().top - 60 + 'px' },
  n);
}

function reload() {
  window.location.removedByCodePen();
}

function correctPositions() {
  $('.meeting.current.empty').remove();
  $('.meeting').each(function () {
    var sT = $(this).attr('data-st'),eT = $(this).attr('data-et');
    var sD = new Date(sT),eD = new Date(eT),D = new Date();
    if (D > eD) $(this).addClass('passed');

    if (sD < D && D < eD) {
      $(this).addClass('current');
      occupied = true;
    }
  });

  if (occupied) {
    $('.top.right h2').addClass('occupied').html('•&nbsp;OCCUPIED');
  } else {
    $('.top.right h2').addClass('available').html('•&nbsp;AVAILABLE');

    // add in-betweener
    var inserted = 0;

    $('.meeting').each(function () {
      var sT = $(this).attr('data-st'),eT = $(this).attr('data-et');
      var sD = new Date(sT),eD = new Date(eT),D = new Date();

      if (D > eD) return;

      if (!inserted) {
        $(this).before(availableString);
        inserted = 1;
      }
    });

    if ($('.meeting.current').length === 0) {
      $('.meeting').last().after(availableString);
      inserted = 1;
    }

    UP();
  }
}

$(document).ready(function () {
  $('.meetings').addClass('in');

  $('.top.right h1').html(locationDisplayString || "Meeting Room");
  $('.bg.cover').attr('src', backgroundImage || "https://images.unsplash.com/photo-1464621922360-27f3bf0eca75?h=768&amp;w=1024");

  setInterval(UP, 15000);
  setInterval(updateClock, 30000);
  setInterval(correctPositions, 60000);
  setTimeout(reload, 300000);

  updateClock();
  correctPositions();

});

function tp(n) {
  return n > 9 ? "" + n : "0" + n;
}

function hrs(n) {
  return n === 0 || n === 24 ? 12 : n > 12 ? n - 12 : n;
}

function ampm(n) {
  return n > 12 ? 'pm' : 'am';
}

function dayOfWeek(i) {
  return ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"][i];
}

function month(i) {
  return ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"][i];
}

function updateClock() {
  var t = new Date();
  var time = hrs(t.getHours()) + ":" + tp(t.getMinutes()) + ampm(t.getHours());
  var date = dayOfWeek(t.getDay()) + ", " + month(t.getMonth()) + " " + t.getDate() + ", " + t.getFullYear();
  $('.bottom.right h1').html(date);
  $('.bottom.right h2').html(time);
}
//# sourceURL=pen.js
    </script>

</body>

</html>