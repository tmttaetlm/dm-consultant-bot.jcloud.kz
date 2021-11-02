/* 
 * Main Javascript file
 */
'use strict'

//Create a storage object
var isStorage = {},
    timerID = 0,
    today = getInputDate(),
    thatTime = getInputTime(21600);

function getInputDate(offset){
    offset = offset || 0;
    var date = new Date();
    date.setDate(date.getDate() + offset);
    return date.toISOString().substring(0, 10);
}
function getInputTime(offset){
    offset = offset || 0;
    var date = new Date();
    date.setTime(date.getTime() + offset);
    return date.toTimeString().substring(0, 5);
}

window.onload = function() {
    
    //Catches clicks and send to handler
    document.addEventListener("click", function (event) {
        clickHandler(event.target);
    });
    
    //Catches changes and send to handler
    document.addEventListener("change", function (event) {
        changeHandler(event.target);
    });

    //Catches getting focus
    document.addEventListener("focusin", function (event) {
        mask(event);
    });

    document.addEventListener('focusout', function (event) {
        mask(event);
    });   

    //Отлавливает ввод с клавиатуры и передает в обработчик
    document.body.onkeyup = function(event) {
        mask(event);
    };

    if (document.getElementById('date')) {
        document.getElementById('date').value = today;
        document.getElementById('time').value = thatTime;
    }

}

//Функция отправки Ajax запроса на сервер
function ajax(queryString, callback, params)
{
    var f = callback||function(data){};
    var request = new XMLHttpRequest();
    request.onreadystatechange = function()
    {
            if (request.readyState == 4 && request.status == 200)
            {
                f(request.responseText);
            }
    }
    request.open('POST', queryString);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(params);
}

//Функция отправки Ajax запроса на сервер +JSON
function ajaxJson(queryString, callback, dataObject)
{
    var f = callback||function(data){};
    var data = 'data='+JSON.stringify(dataObject);
    var request = new XMLHttpRequest();
    request.onreadystatechange = function()
    {
            if (request.readyState == 4 && request.status == 200)
            {
                f(JSON.parse(request.responseText));
            }
    }
    
    request.open('POST', queryString);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(data);
}

//Change Handler
function changeHandler(obj)
{
    
}

//Обработчик кликов на странице
function clickHandler(obj)
{
    if (obj.id == 'deleteUser') {
        if (confirm('Удалить пользователя?')) {
            var params = 'tgId='+obj.parentNode.parentNode.cells[2].innerText;
            ajax('/users/deleteUser', function(data){
    			alert('Пользователь удален');
            }, params);
        }
    }
    
    if (obj.id == 'setAdmin') {
        if (document.getElementById('setName').value == '') {
            var name = document.getElementById('selectAdmin').selectedOptions[0].innerText;
            name = name.substring(name.indexOf('|')+2, name.length);
        } else {
            var name = document.getElementById('setName').value;
        }
        var params = 'id='+document.getElementById('selectAdmin').selectedOptions[0].value+'&name='+name;
        ajax('/params/changeAdmin', function(data){
            console.log(data);
            alert('Администратор изменен на "'+document.getElementById('selectAdmin').selectedOptions[0].innerText+'"');
        }, params);
    }

    if (obj.id == 'deleteSelected') {
        var table = obj.parentNode.previousSibling;
        var idArr = '(';
        for (let i = 1; i < table.rows.length; i++) {
            var row = table.rows[i];
            var select = row.cells[0].children[0];
            if (select.checked) {
                idArr += row.dataset.rowId+', ';
            }
        }
        idArr += ')';
        idArr = idArr.replace(', )', ')');
        if (idArr != '()') {
            if (confirm('Удалить выбранных пользователей?')) {
                var params = 'idArr='+idArr;
                ajax('/offices/deleteOffices', function(data){
                    console.log(data)
                }, params);
            }
        } else {
            alert('Ничего не выбрано!');
        }
    }
}

function mask(event) {
    if (event.target.type == 'tel') {
        var pos = event.target.selectionStart;
        if (pos < 3) event.preventDefault();
        var matrix = "+7 (___) ___ ____",
            i = 0,
            def = matrix.replace(/\D/g, ""),
            val = event.target.value.replace(/\D/g, ""),
            new_value = matrix.replace(/[_\d]/g, function(a) {
                return i < val.length ? val.charAt(i++) || def.charAt(i) : a
            });
        i = new_value.indexOf("_");
        if (i != -1) {
            i < 5 && (i = 3);
            new_value = new_value.slice(0, i)
        }
        var reg = matrix.substr(0, event.target.value.length).replace(/_+/g,
            function(a) {
                return "\\d{1," + a.length + "}"
            }).replace(/[+()]/g, "\\$&");
        reg = new RegExp("^" + reg + "$");
        if (!reg.test(event.target.value) || event.target.value.length < 5 || event.keyCode > 47 && event.keyCode < 58) {
            event.target.value = new_value;
        };
        if (event.type == "focusout" && event.target.value.length < 5)  {
            event.target.value = "";
        }
    }
}
