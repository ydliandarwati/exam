// for slideshow
let slideIndex = 0;
showSlides();

// function to change slideshow pictures every 3 seconds
function showSlides() {

  const slides = document.getElementsByClassName("mySlides");
  // not to show slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    

  // show an slide
  slides[slideIndex-1].style.display = "block";  
  setTimeout(showSlides, 3000); // Change image every 3 seconds
}


// let input = document.querySelector("#livesearch");
// input.addEventListener('keyup',() => {
//     let str = document.querySelector('#livesearch').value;
//     if (str.length==0) {
//       document.getElementById("livesearch").innerHTML="";
//       document.getElementById("livesearch").style.border="0px";
//       return;
//     }
//     var xmlhttp=new XMLHttpRequest();
//     xmlhttp.onreadystatechange=function() {
//       if (this.readyState==4 && this.status==200) {
//         document.getElementById("livesearch").innerHTML=this.responseText;
//         document.getElementById("livesearch").style.border="1px solid #A5ACB2";
//       }
//     }
//     xmlhttp.open("GET","views/livesearch.php?q="+str,true);
//     xmlhttp.send();
//   }
// )
