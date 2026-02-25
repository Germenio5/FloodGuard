// bridge filter helper used on both user and admin water level pages
function filterByBridge(bridgeValue) {
    if (bridgeValue) {
        window.location.href = '?bridge=' + encodeURIComponent(bridgeValue);
    } else {
        window.location.href = '?';
    }
}
