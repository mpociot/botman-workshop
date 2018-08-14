/*!
 * Laracon EU 2018
 * 
 * @author humanmusic.eu
 * @version 0.0.0q
 * Copyright 2018.  licensed.
 */
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

window.addEventListener('load', function () {
  // Add fastclick for iOS
  document.addEventListener('DOMContentLoaded', function () {
    FastClick.attach(document.body);
  }, false);

  // Handle sticky scroll options
  //
  // var threshold = 200;
  // var handleStickyScrollOptions = debounce(function () {
  //   if (document.body.scrollTop > threshold) {
  //     document.getElementById('scrollOptions').className = 'scroll-options scroll-options--animate-in';
  //   } else if (document.body.scrollTop == 0) {
  //     var scrollOptions = document.getElementById('scrollOptions');
  //     scrollOptions.className = 'scroll-options scroll-options--animate-out';
  //   }
  // }, 100);
  // document.addEventListener("scroll", handleStickyScrollOptions);

  // Made iframe load "async"
  var vidDefer = document.getElementsByTagName('iframe');
  for (var i = 0; i < vidDefer.length; i++) {
    if (vidDefer[i].getAttribute('data-src')) {
      vidDefer[i].setAttribute('src', vidDefer[i].getAttribute('data-src'));
    }
  }

  // Scroll to top
  // document.getElementById("scrollToTop").addEventListener("click", function (e) {
  //   e.preventDefault();
  //   scrollTo(document.body, 0, 400);
  //   document.getElementById('top-bar').focus();
  // });
  // var scrollTimeout;
  //
  // function scrollTo(element, to, duration) {
  //   if (duration <= 0) return;
  //   var difference = to - element.scrollTop;
  //   var perTick = difference / duration * 10;
  //
  //   scrollTimeout = setTimeout(function () {
  //     element.scrollTop = element.scrollTop + perTick;
  //     if (element.scrollTop == to) {
  //       clearTimeout(scrollTimeout);
  //     }
  //     scrollTo(element, to, duration - 10);
  //   }, 10);
  // }
});