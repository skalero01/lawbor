(function () {
  "use strict";

  /* page loader */
  function hideLoader() {
    const loader = document.getElementById("loader");
    loader.classList.add("loader-disable")
  }

  window.addEventListener("load", hideLoader);
  /* page loader */


  /* popover  */
  const popoverTriggerList = document.querySelectorAll(
    '[data-bs-toggle="popover"]'
  );
  const popoverList = [...popoverTriggerList].map(
    (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
  );

  /* Choices JS */
  document.addEventListener("DOMContentLoaded", function () {
    var genericExamples = document.querySelectorAll("[data-trigger]");
    for (let i = 0; i < genericExamples.length; ++i) {
      var element = genericExamples[i];
      new Choices(element, {
        allowHTML: true,
        placeholderValue: "This is a placeholder set in the config",
        searchPlaceholderValue: "Search",
      });
    }
  });
  /* Choices JS */

  /* footer year */
  document.getElementById("year").innerHTML = new Date().getFullYear();
  /* footer year */

  /* node waves */
  Waves.attach(".btn-wave", ["waves-light"]);
  Waves.init();
  /* node waves */

  /* card with close button */
  let DIV_BOX = ".box";
  let boxRemoveBtn = document.querySelectorAll(
    '[data-bs-toggle="box-remove"]'
  );
  boxRemoveBtn.forEach((ele) => {
    ele.addEventListener("click", function (e) {
      e.preventDefault();
      let $this = this;
      let box = $this.closest(DIV_BOX);
      box.remove();
      return false;
    });
  });
  /* card with close button */

  /* card with fullscreen */
  let boxFullscreenBtn = document.querySelectorAll(
    '[data-bs-toggle="box-fullscreen"]'
  );
  boxFullscreenBtn.forEach((ele) => {
    ele.addEventListener("click", function (e) {
      let $this = this;
      let box = $this.closest(DIV_BOX);
      box.classList.toggle("box-fullscreen");
      box.classList.remove("box-collapsed");
      e.preventDefault();
      return false;
    });
  });
  /* card with fullscreen */

  /* count-up */
  var i = 1;
  setInterval(() => {
    document.querySelectorAll(".count-up").forEach((ele) => {
      if (ele.getAttribute("data-count") >= i) {
        i = i + 1;
        ele.innerText = i;
      }
    });
  }, 10);
  /* count-up */

  /* back to top */
  const scrollToTop = document.querySelector(".scrollToTop");
  const $rootElement = document.documentElement;
  const $body = document.body;
  window.onscroll = () => {
    const scrollTop = window.scrollY || window.pageYOffset;
    const clientHt = $rootElement.scrollHeight - $rootElement.clientHeight;
    if (window.scrollY > 100) {
      scrollToTop.style.display = "flex";
    } else {
      scrollToTop.style.display = "none";
    }
  };
  scrollToTop.onclick = () => {
    window.scrollTo(0, 0);
  };
  /* back to top */
})();

/* full screen */
var elem = document.documentElement;
window.openFullscreen = function() {
  if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
    requestFullscreen();
  } else {
    exitFullscreen();
  }
}
function requestFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) {
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) {
    elem.msRequestFullscreen();
  }
}
function exitFullscreen() {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) {
    document.msExitFullscreen();
  }
}
// Listen for fullscreen change event
document.addEventListener("fullscreenchange", handleFullscreenChange);
function handleFullscreenChange() {
  
  let open = document.querySelector(".full-screen-open");
  let close = document.querySelector(".full-screen-close");

  if (document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
    // Update icon for fullscreen mode
    close.classList.add("block");
    close.classList.remove("hidden");
    open.classList.add("hidden");
  } else {
    // Update icon for non-fullscreen mode
    close.classList.remove("block");
    open.classList.remove("hidden");
    close.classList.add("hidden");
    open.classList.add("block");
  }
}
/* full screen */

/* toggle switches */
let customSwitch = document.querySelectorAll(".toggle");
customSwitch.forEach((e) =>
  e.addEventListener("click", () => {
    e.classList.toggle("on");
  })
);
/* toggle switches */

/* header dropdown close button */

/* for cart dropdown */
const headerbtn = document.querySelectorAll(".dropdown-item-close");
headerbtn.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    button.parentNode.parentNode.parentNode.parentNode.parentNode.remove();
    document.getElementById("cart-data").innerText = `${document.querySelectorAll(".dropdown-item-close").length
      } `;
    document.getElementById("cart-icon-badge").innerText = `${document.querySelectorAll(".dropdown-item-close").length
      }`;
    console.log(
      document.getElementById("header-cart-items-scroll").children.length
    );
    if (document.querySelectorAll(".dropdown-item-close").length == 0) {
      let elementHide = document.querySelector(".empty-header-item");
      let elementShow = document.querySelector(".empty-item");
      elementHide.classList.add("hidden");
      elementShow.classList.remove("hidden");
    }
  });
});
/* for cart dropdown */

/* for notifications dropdown */
const headerbtn1 = document.querySelectorAll(".dropdown-item-close1");
headerbtn1.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    button.parentNode.parentNode.parentNode.parentNode.remove();
    document.getElementById("notifiation-data").innerText = `${document.querySelectorAll(".dropdown-item-close1").length
      } Unread`;
    if (document.querySelectorAll(".dropdown-item-close1").length == 0) {
      let elementHide1 = document.querySelector(".empty-header-item1");
      let elementShow1 = document.querySelector(".empty-item1");
      elementHide1.classList.add("hidden");
      elementShow1.classList.remove("hidden");
    }
  });
});
/* for notifications dropdown */
