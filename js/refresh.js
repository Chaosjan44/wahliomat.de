load();
check();
startTimer();
var timer;
function startTimer() {
    timer = setInterval(
        function() {
            check()
        }, 
    1000); // 1 second
}

function load() {
    document.getElementById("poll_div").innerHTML = '<h3 class="display-6 text-center text-kolping-orange">Laden...</h3>';
}
var url_string = window.location.href;
var url = new URL(url_string);
var uni = url.searchParams.get("uni");
var geturl = "/php/check.php?uni=" + uni;
function check() {
    const xhttp = new XMLHttpRequest();
    xhttp.open("GET", geturl);
    xhttp.send();
    xhttp.onload = function() {
        if (this.responseText.includes('refresh_true')) {
            loadContent();
        } else {
        }
    }
}

function loadContent() {
    const xhttp2 = new XMLHttpRequest();
    xhttp2.open("GET", "/php/refresher.php");
    xhttp2.send();
    xhttp2.onload = function() {
        if (this.responseText.includes('refresh_now_true')) {
            document.getElementById("poll_div").innerHTML = this.responseText;
        } else {

        }
    }
}