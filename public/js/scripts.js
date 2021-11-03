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
    if (obj.id == 'selectRegion') {
        var param = 'region='+obj.selectedOptions[0].value;
        ajax('/offices/getCities', function(data){
            document.getElementById('selectCity').outerHTML = data;
        }, param);
    }
}

//Обработчик кликов на странице
function clickHandler(obj)
{
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
        var dbtable = document.URL.substring(document.URL.lastIndexOf('/')+1, document.URL.length);
        var table = document.getElementById(dbtable+'List');
        if (table == null) {
            table = obj.parentElement.parentElement.children[0];
            dbtable = 'tickets';
        }
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
            switch (dbtable) {
                case 'users':
                    var msg = 'Удалить выбранных пользователей?';
                    var method = '/users/deleteusers';
                    break;
                case 'offices':
                    var msg = 'Удалить выбранные отделения?';
                    var method = '/offices/deleteOffices';
                    break;
                case 'tickets':
                    var msg = 'Удалить выбранные заявки?';
                    var method = '/tickets/deleteTickets';
                    break;
                default:
                    break;
            }
            if (confirm(msg)) {
                var params = 'idArr='+idArr+'&mode='+table.parentElement.id;
                ajax(method, function(data){
                    console.log(data);
                    table.outerHTML = data;
                    console.log(table);
                }, params);
            }
        } else {
            alert('Ничего не выбрано!');
        }
    }

    if (obj.id == 'addOffice') {
        var params = 'city='+document.getElementById('selectCity').selectedOptions[0].value+'&phone='+document.getElementById('officePhone').value+
            '&adres='+document.getElementById('officeAdres').value+'&mediaUrl='+document.getElementById('officeMediaUrl').value;
        ajax('/offices/addOffice', function(data){
            document.getElementById('officesListMain').outerHTML = data;
            //console.log(data);
        }, params);
    }

    if (obj.id == 'addJewel') {
        var params = 'name='+document.getElementById('jewelsName').value;
        ajax('/jewels/addJewel', function(data){
            document.getElementById('jewelsList').outerHTML = data;
            //console.log(data);
        }, params);
    }
}

function mask(event) {
    if (event.target.type == 'tel') {
        var maskOptions = {
            mask: '+7 000 000 0000',
            lazy: false
        } 
        var mask = new IMask(event.target, maskOptions);
    }
}