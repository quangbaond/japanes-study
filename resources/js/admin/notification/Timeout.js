var timeout;
export const setCountDown = () => {
    console.log("da goi set count down");
    timeout = setTimeout( () => {
        console.log('khong the clear')
    },10000);

}
export const clearCountDown = () => {
    console.log("da goi clear count down");
    setCountDown();
    clearTimeout(timeout);
}

