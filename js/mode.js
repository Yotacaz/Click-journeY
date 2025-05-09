/*Theme par défaut :
    --coul-arr-plan: #261C57;
    --coul-texte: #43aed4;
    --coul-titre: #CA64F9;
    --couleur-gras: #f39639;
    --couleur-em: #fd5bcf;
    --couleur-a: #F205B3;
    --couleur-b: #ffffff40;
    --couleur-c: #ffffff10;
    --couleur-d: #ffffff80;
    --coul-arr-plan-transparent: #261c5799;
    --coul-texte-transparent: #43aed499;
*/
/* Theme inverse :
    --coul-arr-plan: #43aed4;
    --coul-texte: #261C57;
    --coul-titre: #8801c7;
    --couleur-gras: #f39639;
    --couleur-em: #c00089;
    --couleur-a: #d5019c;
    --couleur-b: #ffffff40;
    --couleur-c: #ffffff10;
    --couleur-d: #ffffff80;
    --coul-arr-plan-transparent: #43aed499;
    --coul-texte-transparent: #261C5799;
*/

var theme = getCookie('theme');
if (theme == '') {
    theme = "defaut";
    setCookie("theme", theme);
}


let style = document.documentElement.style;

let inputs_theme = document.querySelectorAll('.modif-theme');


// Modif des couleurs hardcodees
// modifiez ici si vous souhaitez changer les couleurs

function activerThemeInverse() {
    style.setProperty('--coul-arr-plan', '#43aed4');
    style.setProperty('--coul-texte', '#261C57');
    style.setProperty('--coul-titre', '#8801c7');
    style.setProperty('--couleur-gras', '#5C2D03');
    style.setProperty('--couleur-em', '#c00089');
    style.setProperty('--couleur-a', '#d5019c');
    style.setProperty('--couleur-b', '#ffffff40');
    style.setProperty('--couleur-c', '#ffffff10');
    style.setProperty('--couleur-d', '#ffffff80');
    style.setProperty('--coul-arr-plan-transparent', '#43aed499');
    style.setProperty('--coul-texte-transparent', '#261C5799');
    setCookie("theme", "inverse");

    for (let i = 0; i < inputs_theme.length; i++) {
        inputs_theme[i].setAttribute("checked", "checked");
    }
}

function restaurerThemeOriginal() {
    style.setProperty('--coul-arr-plan', '#261C57');
    style.setProperty('--coul-texte', '#43aed4');
    style.setProperty('--coul-titre', '#CA64F9');
    style.setProperty('--couleur-gras', '#f39639');
    style.setProperty('--couleur-em', '#fd5bcf');
    style.setProperty('--couleur-a', '#F205B3');
    style.setProperty('--couleur-b', '#ffffff40');
    style.setProperty('--couleur-c', '#ffffff10');
    style.setProperty('--couleur-d', '#ffffff80');
    style.setProperty('--coul-arr-plan-transparent', '#261c5799');
    style.setProperty('--coul-texte-transparent', '#43aed499');
    setCookie("theme", "defaut");

    for (let i = 0; i < inputs_theme.length; i++) {
        inputs_theme[i].removeAttribute("checked");
    }
}

function changerTheme() {
    theme = getCookie('theme');
    if (theme == "defaut") {
        activerThemeInverse();
    } else if (theme == "inverse") {
        restaurerThemeOriginal();
    }
    else {
        console.warn("Theme inconnu : " + theme + " - utilisation du theme par défaut");
        restaurerThemeOriginal();
    }
}

// enregistrement du theme dans le cookie
function setCookie(name, value) {
    var d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// methode de recuperation du theme dans le cookie
function getCookie(cname) {
    var theme = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(theme) == 0) {
            return c.substring(theme.length, c.length);
        }
    }
    return "";
}

if (theme == "inverse") {
    activerThemeInverse();
}
else if (theme != "defaut") {
    console.warn("Theme non supporté :" + theme);
    setCookie("theme", "defaut");
    theme = "defaut";
}