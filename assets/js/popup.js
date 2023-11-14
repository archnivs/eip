const exit = e => {
    const shouldExit =
        [...e.target.classList].includes('eip-popup') || 
        e.target.className === 'close' || 
        e.keyCode === 27; 

    if (shouldExit) {
        document.querySelector('.eip-popup').classList.remove('visible');
    }
};

const mouseEvent = e => {
    const shouldShowExitIntent = 
        !e.toElement && 
        !e.relatedTarget &&
        e.clientY < 10;

    if (shouldShowExitIntent) {
        document.removeEventListener('mouseout', mouseEvent);
        document.querySelector('.eip-popup').classList.add('visible');

        EIPCookies.setCookie(EIP.cookie_name, true, 30);
    }
};


setTimeout(() => {
    document.addEventListener('mouseout', mouseEvent);
    document.addEventListener('keydown', exit);
    document.querySelector('.eip-popup').addEventListener('click', exit);
}, 0);
/* if (!EIPCookies.getCookie(EIP.cookie_name)) {
    setTimeout(() => {
        document.addEventListener('mouseout', mouseEvent);
        document.addEventListener('keydown', exit);
        document.querySelector('.eip-popup').addEventListener('click', exit);
    }, 0);
} */
