var textInput = $(".textInput")
var cowInput = $(".cowInput")
var translationStatus = $(".status")

var timeout;



function updateCowInput() {

    let result = cowTranslator.textToCow(textInput.value)
    if (result.success) {
        translationStatus.innerText = "OK"
        cowInput.value = result.cow
        if (result.warning) {
            translationStatus.innerText = result.error
        }
    } else {
        translationStatus.innerText = "ERROR"
        cowInput.value = result.error;
    }

}


function updateTextInput() {
    let result = cowTranslator.cowToText(cowInput.value)
    if (result.success) {
        translationStatus.innerText = "OK"
        textInput.value = result.text
        if (result.warning) {
            translationStatus.innerText = result.error
        }
    } else {
        translationStatus.innerText = "ERROR"
        textInput.value = result.error;
    }
}


textInput.oninput = function() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        updateCowInput()
    }, 200);
}
cowInput.oninput = function() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        updateTextInput()
    }, 200);

}

textInput.value = "Enter your text here"

textInput.oninput();