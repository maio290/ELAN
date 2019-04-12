let navbarHidden = true;

function unhideByClass(className) {
    let elements = document.getElementsByClassName(className);
    for (let i = 0; i < elements.length; i++) {
        elements[0].removeAttribute("hidden");
    }

}

function beautifyDate(dateString)
{
    let date = new Date(dateString);
    return formatDateTime(date);
}

function switchExternal(ausbildungsnachweis) {
    let newState = ausbildungsnachweis['Freigabestatus'];
    let buttonWrapper = document.getElementById('buttonWrapper');
    switch (Number(newState)) {
        case -1:
        case 0:
        case 1:
        case 2:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"></div>';
            break;
        case 3:
            buttonWrapper.innerHTML = '<div class="centered"><div class="azubiSignature">' +
                '<span>Freigegeben am ' + beautifyDate(ausbildungsnachweis['Freigabedatum']) + '</span>' +
                '</div>' +
                '<div class="ausbilderSignature"><span>Signiert am ' + beautifyDate(ausbildungsnachweis['Signaturdatum']) + ' durch ' + ausbildungsnachweis['Signaturgeber'] + '</span></div>';
            break;

    }
}

function switchButtons(ausbildungsnachweis) {

    let newState = ausbildungsnachweis['Freigabestatus'];
    let buttonWrapper = document.getElementById('buttonWrapper');

    switch (Number(newState)) {
        case -1:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"><button class="speichern" onclick="acquireAndStoreAusbildungsnachweis()">Speichern</button></div>';
            break;
        case 0:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"><button class="speichern" onclick="acquireAndStoreAusbildungsnachweis()">Speichern</button><button class="freigeben" onclick="publish()">Freigeben</button></div>';
            break;
        case 1:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"><button class="freigeben" onclick="unpublish()">Zurückziehen</button></div>';
            break;
        case 2:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"><button class="speichern" onclick="acquireAndStoreAusbildungsnachweis()">Speichern</button><button class="freigeben" onclick="publish()">Freigeben</button></div>';
            break;
        case 3:
            buttonWrapper.innerHTML = '<div id="buttonWrapper">Signiert am ' + ausbildungsnachweis['Signaturdatum'] + ' durch ' + ausbildungsnachweis['Signaturgeber'] + '</div>';
            break;

    }
}

function switchAusbilderButtons(ausbildungsnachweis) {

    let newState = ausbildungsnachweis['Freigabestatus'];
    let buttonWrapper = document.getElementById('buttonWrapper');

    switch (Number(newState)) {
        case 1:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"><button class="green" onclick="sign()">Signieren</button><div id="buttonWrapper"><button class="red" onclick="faulty()">Zur Korrektur geben</button></div>';
            break;
        case 2:
            buttonWrapper.innerHTML = '<div id="buttonWrapper""><span class="red">Korrektur ausstehend</span></div>';
            break;
        case 3:
            buttonWrapper.innerHTML = '<div id="buttonWrapper">Signiert am ' + ausbildungsnachweis['Signaturdatum'] + ' durch ' + ausbildungsnachweis['Signaturgeber'] + '</div>';
            break;
        default:
            buttonWrapper.innerHTML = '<div id="buttonWrapper"></div>';
            break;
    }
}

function appendZero(target) {
    if (target < 10) {
        target = String(target);
        target = "0" + target;
    }
    return target;
}

function toggleAzubiData(ID) {
    let chevron = document.getElementById("chevron-" + ID);


    if (chevron.src.includes("_right")) {
        //hidden
        chevron.src = chevron.src.replace("baseline-chevron_right-24px.svg", "baseline-expand_more-24px.svg");
    } else {
        chevron.src = chevron.src.replace("baseline-expand_more-24px.svg", "baseline-chevron_right-24px.svg");
    }

}

function formatDate(date) {
    let day = date.getDate();
    day = appendZero(day);
    let month = date.getMonth() + 1;
    month = appendZero(month);
    let year = date.getFullYear();
    return day + "." + month + "." + year;
}

function formatDateTime(date) {
    let hour = appendZero(date.getHours());
    let minutes = appendZero(date.getMinutes());
    let seconds = appendZero(date.getSeconds());
    let day = date.getDate();
    day = appendZero(day);
    let month = date.getMonth() + 1;
    month = appendZero(month);
    let year = date.getFullYear();
    return day + "." + month + "." + year +" - "+hour+":"+minutes+":"+seconds;
}

function getWeekday(day) {
    switch (day) {
        case 1:
            return "Montag";
        case 2:
            return "Dienstag";
        case 3:
            return "Mittwoch";
        case 4:
            return "Donnerstag";
        case 5:
            return "Freitag";
        case 6:
            return "Samstag";
        case 0:
            return "Sonntag";
        default:
            return "";
    }
}

function replaceDates(iterator) {
    let start = document.getElementById("start");
    let time = Number(start.innerText);
    time += iterator * 7 * 86400;
    let end = time + 6 * 86400;
    end = new Date(end * 1000);
    start.innerText = time;
    let date = new Date(time * 1000);

    document.getElementsByClassName("duration")[0].innerHTML = formatDate(date) + " bis " + formatDate(end);

    let weekdays = document.getElementsByClassName("weekday");
    for (let i = 0; i <= 6; i++) {
        if (typeof (date) == "number") {
            date = new Date(date);
        }

        let day = date.getDay()
        weekdays[i].innerHTML = getWeekday(day) + ", " + formatDate(date);
        date = date.getTime() + 1000 * 86400
    }
}

function hideByClass(classname) {
    let elements = document.getElementsByClassName(classname);
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.display = 'none';
    }
}

function showClass(classname) {
    let elements = document.getElementsByClassName(classname);
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.display = 'unset';
    }
}

function disableByID(ID) {
    let element = document.getElementById(ID);
    element.setAttribute("disabled", true);
}

function enableByID(ID) {
    let element = document.getElementById(ID);
    element.removeAttribute("disabled");
}

function hideByID(ID) {
    let element = document.getElementById(ID);
    element.setAttribute("hidden", true);
}


function unhideByID(ID) {
    let element = document.getElementById(ID);
    element.removeAttribute("hidden");
}

function replaceContentByID(ID, content) {
    let element = document.getElementById(ID);
    element.innerText = content;

}

function replaceValueByID(ID, content) {
    let element = document.getElementById(ID);
    element.value = content;
}

function resetClassesByID(ID) {
    let element = document.getElementById(ID);
    element.className = "";
}

function addClassByID(ID, targetClass) {
    let element = document.getElementById(ID);
    element.className = targetClass;
}

function focus(id) {
    document.getElementById(id).focus();
}

function toggleInformationBar(ID, message, targetClass) {
    if (navbarHidden) {
        replaceContentByID(ID, message);
        resetClassesByID(ID);
        unhideByID("informationBar");
        unhideByID(ID);
        addClassByID(ID, targetClass);
        focus("informationBar");

        navbarHidden = false;
        setTimeout(toggleInformationBar, 4000, ID, message, targetClass);
    } else {
        hideByID("informationBar");
        hideByID(ID);
        navbarHidden = true;
    }


}