load();
check();
startTimer();
var timer;
function startTimer() {
    timer = setInterval(
        function() {
            check()
        }, 
    5000); // 5 seconds
}

function load() {
    document.getElementById("poll_div").innerHTML = '<h3 class="display-6 text-center d-flex justify-content-center align-items-center text-kolping-orange mb-0">Laden <div style="width: 1.5rem; height: 1.5rem;" class="spinner-grow text-kolping-orange mx-2" role="status"><span class="sr-only"></span></div></h3><h4 class="text-center text-kolping-orange">(ca. 5 Sekunden)</h4>';
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