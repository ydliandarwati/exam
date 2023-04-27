let input = document.querySelector("#search");

input.addEventListener('keyup',() => {
        //get the id of input field
        let textFind = document.querySelector('#search').value;
        //faire un objet de type request
        let req = new Request('index.php?road=searchAjax', {
            method: 'POST',
            body : JSON.stringify({ textToFind : textFind })
        });  
        
        
            
        //   using the reponse
        fetch(req)
            .then(result => result.text())
            .then(result => { document.querySelector('#target').innerHTML = result;});
    }
);