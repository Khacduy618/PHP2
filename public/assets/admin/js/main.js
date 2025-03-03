// add hovered class to selected list item
let list = document.querySelectorAll(".navbar li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navbar");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};

function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

function time() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
  var t = setTimeout(function () { time() }, 500);
}

window.onload = time;

function toggleDacBiet(checkbox) {
  checkbox.value = checkbox.checked ? 1 : 0;
}
let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) { slideIndex = 1 }
  if (n < 1) { slideIndex = slides.length }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
}

$(document).ready(function () {
  $('#onViewAll').on('click', function (e) {
    e.preventDefault();
    $('.all').removeClass('all');
    $('#limit').hide();
    $('#onViewAll').hide();
  });
  $('#onViewAll-table').on('click', function (e) {
    e.preventDefault();
    $('.all-table').removeClass('all-table');
    $('#limit-table').hide();
    $('#onViewAll-table').hide();
  });

  

  $('#product_discount').on('input change', function() {
    const $input = $(this);
    let value = parseInt($input.val());
    
    if (isNaN(value) || value < 0) {
      $input.val(0);
    } else if (value > 100) {
      $input.val(100);
    }
  });
  $('#product_count').on('input change', function() {
    const $input = $(this);
    let value = parseInt($input.val());
    
    if (isNaN(value) || value < 0) {
      $input.val(0);
    }
  });
  $('#product_price').on('input change', function() {
    const $input = $(this);
    let value = parseInt($input.val());
    
    if (isNaN(value) || value < 0) {
      $input.val(0);
    }
  });
});