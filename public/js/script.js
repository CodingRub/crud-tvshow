/* // Get the modal
var modal = document.querySelector(".form-popup");

// Get the button that opens the modal
var btn = document.querySelectorAll('#editBtn');

// Get the <span> element that closes the modal
var span = document.querySelector(".close");

// When the user clicks on the button, open the modal
btn[0].onclick = function() {
  modal.style.display = "flex";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
*/



// Get each modal and close button
const triggers = document.getElementsByClassName("trigger");
const triggerArray = Array.from(triggers).entries();
const modals = document.getElementsByClassName("modal");
const closeButtons = document.getElementsByClassName("btn-close");

// Then use `for...of`-loop with the index of each item in `triggerArray` for listening to a click event which toggles each modal to open and close
for (let [index, trigger] of triggerArray) {
    const toggleModal => () {
        modals[index].classList.toggle("show-modal");
    };
    trigger.addEventListener("click", toggleModal);
    closeButtons[index].addEventListener("click", toggleModal);
}