let runningTotal = 0;
let buffer = "0";
let previousOperator;

const screen = document.querySelector('.screen');

function buttonClick(value) {
    /*isnan is not a number */
    /* ถ้าเป็นตัวเลขเปลี่ยน 0 เป็นตัวเลขอื่น */
    /* ถ้าเป็นเครื่องหมายลงไปทำข้างล่าง */
    if(isNaN(value)) {
        handleSymbol(value);
    } else {
        handleNumber(value);
    }
    screen.innerText = buffer;
}

function handleSymbol(symbol){
    switch(symbol){
        case 'C':
            buffer ='0';
            runningTotal=0;
            break;
        case '=':
            if(previousOperator === null){
                return
            }
            flushOperation(parseInt(buffer));
            previousOperator = null;
            buffer = runningTotal;
            runningTotal = 0;
            break;
        case '←':
            if(buffer.length ===1){
                buffer ='0';
            }else{
                buffer=buffer.substring(0, buffer.length -1);

            }
            break;
        case '+':
        case '−':
        case '×':
        case '÷':
            handleMath(symbol)
            break;      
    }
}
/* ถ้าเป็นเคส + - * / ทำด้านล่างนี้ */
function handleMath(symbol) {
    if(buffer === '0'){
        return;
    }

    const intBuffer = parseInt(buffer);

    if(runningTotal === 0){
        runningTotal =intBuffer;
    }else{
        flushOperation(intBuffer);
    }
    previousOperator = symbol;
    buffer = '0';

}

function flushOperation(intBuffer){
    if(previousOperator === '+'){
        runningTotal += intBuffer;
    }else if(previousOperator === '−'){
        runningTotal -= intBuffer;
    }else if(previousOperator === '×'){
        runningTotal *= intBuffer;
    }else if(previousOperator === '÷'){
        runningTotal /= intBuffer;
    }
}

function handleNumber(numberString){
    if(buffer === "0"){
        buffer =numberString;
    }else{
        /* เช่นกด1 กด 2 จะได้ 12 */
        buffer += numberString
    }
}

function init(){
    document.querySelector('.calc-buttons').addEventListener('click', function(event){
        buttonClick(event.target.innerText);

    })

}

init();
