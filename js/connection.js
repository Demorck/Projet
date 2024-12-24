
function changeConType(theSwitch){
    const connectionDiv = document.getElementById('connectionDiv');

    if (theSwitch.checked) {
        document.getElementById('loginForm').classList.remove('hidden');
        document.getElementById('registerForm').classList.add('hidden');
    } else {
        document.getElementById('registerForm').classList.remove('hidden');
        document.getElementById('loginForm').classList.add('hidden');
    }
}
