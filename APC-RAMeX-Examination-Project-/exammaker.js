let questionNumber = 1; // Initialize question number

function addSection() {
    const node = document.querySelector(".section1");
    const clone = node.cloneNode(true);
    var num = document.getElementById("section_number")

    document.getElementById("main-container").appendChild(clone);
    questionNumber = 0;
}

function addQuestion() {
    const node = document.querySelector(".question-container");
    const clone = node.cloneNode(true);
    const questionNumbers = clone.querySelectorAll(".question_number");

    // Increment question number for each clone
    questionNumbers.forEach(number => {
    number.textContent = ++questionNumber;
     });

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
