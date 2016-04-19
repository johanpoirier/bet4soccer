/**
 *
 */
function over_effect(e,state){
    if(document.all)
        source4=event.srcElement
    else if(document.getElementById)
        source4=e.target
    if(source4.className=="menulines")
        source4.style.borderStyle=state
    else {
        while(source4.tagName!="TABLE"){
            source4=document.getElementById? source4.parentNode : source4.parentElement
            if (source4.className=="menulines")
                source4.style.borderStyle=state
        }
    }
}

/**
 *
 */
function showHide(id) {
    var div = document.getElementById(id);
    div.style.display = (div.style.display=='none')?'block':'none';
}

/**
 *
 */
function showPny(idMatch) {
    var scoreA = $('#scoreTeam_A_'+idMatch).val();
    var scoreB = $('#scoreTeam_B_'+idMatch).val();

    if((scoreA.length > 0) && (scoreB.length > 0) && (scoreA == scoreB)) {
        $('#rbPny_A_'+idMatch).val('A');
        $('#rbPny_B_'+idMatch).val('B');
        $('#pny_'+idMatch).show();
    }
    else {
        $('#pny_'+idMatch).hide();
        $('#rbPny_A_'+idMatch).val(null);
        $('#rbPny_B_'+idMatch).val(null);
    }
}

/**
 *
 */
function checkScore(event) {
    var input = event.target;
    if (input) {
        var value = parseInt(input.value, 10);
        if ((value === 1) || (value === 2) || (value === 4)) {
            input.classList.add('invalid');
            input.setAttribute('title', 'Le score est invalide');
        } else {
            input.classList.remove('invalid');
            input.removeAttribute('title');
        }
    } else {
        console.debug("input #" + id + " not found");
    }
}

function saveTag(userTeamID) {
    if(!userTeamID) userTeamID = -1;
    tag = $("#tag").val();

    $.ajax({
        type: "POST",
        url: "/include/classes/ajax.php",
        data: "op=saveTag&userTeamID=" + userTeamID + "&tag=" + tag,
        success: function(data) {
            $("#tags").html(data);
            $("#tag").val("");
        }
    });

    return false;
}

function delTag(tagID, userTeamID) {
    if(confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
        if(!userTeamID) userTeamID = -1;
        $.ajax({
            type: "POST",
            url: "/include/classes/ajax.php",
            data: "op=delTag&tagID=" + tagID + "&userTeamID=" + userTeamID,
            success: function(data) {
                $("#tags").html(data);
            }
        });
    }
}

function loadTags(userTeamID, startTag) {
    if (typeof(userTeamID) === 'function') {
        userTeamID = false;
    }
    $.ajax({
        type: "POST",
        url: "/include/classes/ajax.php",
        data: "op=getTags&start=" + (startTag || 0) + "&userTeamID=" + (userTeamID || -1),
        success: function(data) {
            $('#tags').html(data);
        }
    });

    return false;
}

function displayAutoJoin(userTeamName, code) {
    $("#code").val(code);
    if(confirm("Souhaitez-vous rejoindre le groupe '" + userTeamName + "' ?")) {
        $("form#join_group_form").submit();
    }
}

function getUser(idUser) {
    $.ajax({
        type: "GET",
        url: "/include/classes/ajax.php",
        data: "op=getUser&id=" + idUser,
        success: fillUser
    });
}

var fillUser = function(response) {
    userDatas = response.split("|");
    $('#name').val(userDatas[0]);
    $('#login').val(userDatas[1]);
    $('#mail').val(userDatas[2]);
    $('#admin').attr('checked', (userDatas[3] == 1));
    selectListValue('sltUserTeam', userDatas[4]);
}

function selectListValue(id_liste, value) {
    $("#"+id_liste+" option[value='"+value+"']").attr('selected', 'selected');
}

function selectRadioValue(name, value) {
    $("input[name=" + name + "]").each(function() {
        if($(this).val() == value) {
            $(this).attr("checked", "checked");
        }
    });
}