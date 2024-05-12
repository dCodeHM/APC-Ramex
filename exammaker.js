function addSection() {
    const node = document.querySelector(".section1");
    const clone = node.cloneNode(true);
    var num = document.getElementById("section_number")

    document.getElementById("main-container").appendChild(clone);
    // num.innerText = 
}

function addQuestion() {
        const node = document.querySelector(".question-container");
        const clone = node.cloneNode(true);
        var num1 = document.getElementById("num1");
    
        document.getElementById("main-container").appendChild(clone);

}

function insertQuestion(event){
    const node = document.querySelector(".question-container");
    const clone = node.cloneNode(true);
    var num1 = document.getElementById("num1");

    var targ = event.target || event.srcElement;

    document.getElementById("main-container").appendChild(clone);
    document.getElementById("textbox").value += targ.textContent || targ.innerText;
}
