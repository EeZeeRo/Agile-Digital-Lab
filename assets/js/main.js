document.getElementById("nav-head").addEventListener("click", showNavPopup);

let clicked;
function showNavPopup() {
    if (!clicked) {
        clicked = true;
        document.getElementById("nav-popup").style.display = 'block'
    } else if (clicked) {
        clicked = false;
        document.getElementById("nav-popup").style.display = 'none'
    }
}