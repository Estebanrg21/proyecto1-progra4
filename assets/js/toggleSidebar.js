/*
=========================================================
* Soft UI Dashboard - v1.0.5
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
*/

"use strict";

const iconNavbarSidenav = document.getElementById("iconNavbarSidenav")
  , iconSidenav = document.getElementById("iconSidenav")
  , sidenav = document.getElementById("sidenav-main");
let body = document.getElementsByTagName("body")[0]
  , className = "g-sidenav-pinned";
function toggleSidenav() {
    body.classList.contains(className) ? (body.classList.remove(className),
    sidenav.classList.remove("bg-transparent")) : (body.classList.add(className),
    sidenav.classList.remove("bg-transparent"),
    iconSidenav.classList.remove("d-none"))
}
iconNavbarSidenav && iconNavbarSidenav.addEventListener("click", toggleSidenav),
iconSidenav && iconSidenav.addEventListener("click", toggleSidenav);

