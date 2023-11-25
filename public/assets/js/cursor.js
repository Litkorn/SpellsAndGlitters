window.addEventListener('mousemove', function(e) {
  var arr = [0.5, 0.2];
  var scrollX = window.scrollX || window.pageXOffset;
  var scrollY = window.scrollY || window.pageYOffset;

  arr.forEach(function(i) {
    var x = (1 - i) * 75;
    var star = document.createElement('div');

    star.className = 'star';
    star.style.top = e.pageY - scrollY + Math.round(Math.random() * x - x / 2) + 'px';
    star.style.left = e.pageX - scrollX + Math.round(Math.random() * x - x / 2) + 'px';

    document.body.appendChild(star);

    window.setTimeout(function() {
      document.body.removeChild(star);
    }, Math.round(Math.random() * i * 1500));
  });
}, false);
