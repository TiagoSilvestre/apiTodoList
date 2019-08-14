/**
 * Return the todoList objetct from db
 * @param bool $haveToPrint If have to print on the screen
 * Se este usuario nao tem uma lista gravada ele salva uma vazia
 * @returns array [{}]
 */
function getList(haveToPrint) {
    var jwt = getCookie('jwt');
    $.ajax({
        url: urlBase + "/todolist/getList.php",
        method: "POST",
        data: JSON.stringify({jwt: jwt}),
    }).done(function (result) {
        result = (result) ? JSON.parse(result) : [];
        if (haveToPrint) {
            printList(result);
        }
        return result;
    }).fail(function (result) {
        console.log('Error: ', result);
    });
}

/**
 * 
 * @param {array} list
 * @returns {undefined}
 */
function setList(list) {
    var jwt = getCookie('jwt');
    $.ajax({
        url: urlBase + "/todolist/save.php",
        method: "POST",
        data: JSON.stringify({jwt: jwt, content: list}),
    }).done(function (result) {
        getList(true);
    }).fail(function (result) {
        console.log('Error: ', result);
    });
}

function printList(list) {
    if (list) {
        document.getElementById('lista').innerHTML = '<tr><th>Atividade</th><th>Concluído</th><th>Excluir</th></tr>';
        list.forEach((item, index) => {
            document.getElementById('lista').innerHTML += `
                <tr>
                    <td>` + item.name + `</td>
                    <td onclick="finished(${index})">
                        ` + generateInput(item.done) + `
                    </td>
                    <td class="point" onclick="excluir(${index})">Excluir</td>
                </tr>                 
            `;
        });
    }
}
/**
 * Creates the input for the checkbox field
 */
function generateInput(isDone) {
    if (isDone) {
        return "<input type='checkbox' checked>";
    } else {
        return "<input type='checkbox'>";
    }
}

/**
 * Remove um elemento da lista
 */
function excluir(index) {
    var jwt = getCookie('jwt');
    $.ajax({
        url: urlBase + "/todolist/getList.php",
        method: "POST",
        data: JSON.stringify({jwt: jwt}),
    }).done(function (result) {
        result = JSON.parse(result);
        setList(removeArray(result, index));
    });
}

function removeArray(array, index) {
    return array.slice(0, index).concat(array.slice(index + 1));
}

function finished(index) {
    var jwt = getCookie('jwt');
    $.ajax({
        url: urlBase + "/todolist/getList.php",
        method: "POST",
        data: JSON.stringify({jwt: jwt}),
    }).done(function (result) {
        result = JSON.parse(result);
        result[index].done = !result[index].done;
        setList(result);
    });
}

/**
 * Save list
 */
$("#addInList").click(function(e) {
    e.preventDefault();
    let name = document.getElementById("itemName").value;
    appendList(name);
    document.getElementById("itemName").value = '';
});


function appendList(name) {
    var jwt = getCookie('jwt');
    $.post(urlBase + "/todolist/getList.php", JSON.stringify({jwt: jwt})).done(function (result) {
        if (result) {
            result = JSON.parse(result);
            result.push({'name': name, 'done': false});
            setList(result);
        } else {
            setList([{'name': name, 'done': false}]);
        }
    }).fail(function (result) {
        console.log(result)
        alert('Houveram erros ao salvar');
    });
}
