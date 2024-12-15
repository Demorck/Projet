const inscriptionContent = `             
<form action="/register" method="post">
    
    <fieldset>
        

        
        <legend>Informations personnelles</legend>
        <br>
        Login : <br> <input type="text" name="login" required="required" class="w-full focus:scale-105 duration-300 border border-gray-400"><br><br>   
        Mot de passe : <br><input type="text" name="password" required="required" class="w-full focus:scale-105 duration-300 border border-gray-400"><br><br>
        Nom : <br><input type="text" name="last_name" class="w-full focus:scale-105 duration-300 border border-gray-400"><br><br>   
        Prénom : <br><input type="text" name="first_name" class="w-full focus:scale-105 hover:scale-105 duration-300 border border-gray-950"><br><br>
        Genre : <br>
        <input type="radio" name="gender" value="h"> Homme 	
        <input type="radio" name="gender" value="f"> Femme
        <input type="radio" name="gender" value="v"> Autre/ne se prononce pas
        <br ><br>
        Email : <br><input type="email" id="email" name="email" class="w-full focus:scale-105 duration-300 border border-gray-400"> <br><br>
        Adresse : a faire <br><br>
        
    </fieldset>

    <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" value="Valider" name="submit" >

</form>`;
            
const connectionContent = `
<form action="/login" method="post" >
    <fieldset>
    
        <legend>Informations personnelles</legend>
        <br>
        Login : <br> <input type="text" name="login" required="required" class="w-full focus:scale-105 duration-300 border border-gray-400"><br><br>   
        Mot de passe : <br><input type="text" name="password" required="required" class="w-full focus:scale-105 duration-300 border border-gray-400"><br><br>
        
    </fieldset>

    <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" value="Valider" name="submit" >

</form>
`;
// const connectionToggle = document.getElementById('connectionToggle');

function changeConType(theSwitch){
    const connectionDiv = document.getElementById('connectionDiv');

    if (theSwitch.checked) {
        console.log("checked");
        connectionDiv.innerHTML = connectionContent;
    } else {
        console.log("unchecked");
        connectionDiv.innerHTML = inscriptionContent;
    }
}

