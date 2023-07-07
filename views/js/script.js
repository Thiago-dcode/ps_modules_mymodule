
let button = document.querySelector(".button-submit");
let toggle = true;
button.addEventListener("click", (e) => {
 e.preventDefault();
console.log(prestashop);
  button.style.backgroundColor = toggle ? "red" : "blue";

  toggle = !toggle;
  
});


async function myAjax(param) {
    let result
    try {
      result = await $.ajax({
        url: url_ajax,
        type: 'POST',
        data: param,
      })
      return result
    } catch (error) {
      console.error(error)
    }
  }