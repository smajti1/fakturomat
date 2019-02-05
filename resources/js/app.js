var showHideSwitches = document.querySelectorAll('[data-show-hide]');

for (var i = 0; i < showHideSwitches.length; i++) {
    showHideSwitches[i].addEventListener('click', function (e) {
        e.preventDefault();
        var arrow = this.getElementsByTagName('i')[0];
        var showHideElement = document.getElementById(this.dataset.showHide);

        if (arrow.className.indexOf('down') > 0) {
            arrow.className = arrow.className.replace('down', 'up');
            showHideElement.removeAttribute('hidden');
        } else {
            arrow.className = arrow.className.replace('up', 'down');
            showHideElement.setAttribute('hidden', '');
        }
    });
}