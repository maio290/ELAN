function navigate(path) {
    window.location.href = path;
}


function getAndNavigate(target, dataHolder, AVID) {
    let data = document.getElementById(dataHolder).value;
    navigate(target + '?' + dataHolder + '=' + data + '&AVID=' + AVID);
}