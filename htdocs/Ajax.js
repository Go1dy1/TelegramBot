let servResponse = document.querySelector('#response');

document.forms.ourForm.onsubmit =function (e) {
e.preventDefault();

var userInput = document.forms.ourForm.message.value;

userInput= encodeURIComponent(userInput);

var xhr = new XMLHttpRequest();

xhr.open('POST','Execute.php');

var formData = new FormData(document.forms.ourForm);

//xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

xhr.onreadystatechange = function (){
    if (xhr.readyState === 4 && xhr.status === 200)
    {
        servResponse.textContent = xhr.responseText;
    }
}

xhr.send(formData);
};
