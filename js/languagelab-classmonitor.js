/**
 * *************************************************************************
 * *             OWLL LANGUAGE LAB Version 3 for Moodle 2                 **
 * *************************************************************************
 * @package     mod                                                       **
 * @subpackage  languagelab                                               **
 * @name        languagelab                                               **
 * @copyright   oohoo.biz                                                 **
 * @link        http://oohoo.biz                                          **
 * @author      Nicolas Bretin                                            **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */

//Used to get the flash component
function getFlashMovieObject(movieName)
{
    if (window.document[movieName]) 
    {
        return window.document[movieName];
    }
    if (navigator.appName.indexOf("Microsoft Internet")==-1)
    {
        if (document.embeds && document.embeds[movieName])
            return document.embeds[movieName]; 
        else
            return null;
    }
    else // if (navigator.appName.indexOf("Microsoft Internet")!=-1)
    {
        return document.getElementById(movieName);
    }
}

//Hide or display the player options
function toggleDisplayOptions()
{
    if (document.getElementById('divPlayerOptions').style.position == 'relative')
    {
        
    }
    else
    {
        playeroptions_open();
    }
}

//Put the UserLiveURI in the variable
function getUserLiveURI(liveURI)
{
    userLiveURI = liveURI;
    $("#mylive").text(liveURI);
}

//Put the UserRecordURI in the variable
function getUserRecordURI(recordURI)
{
    userRecordURI = recordURI;
}

//Run this function when player is ready
function playeroptions_ready()
{
    if (playerOptions == undefined)
    {
        playerOptions = getFlashMovieObject('playerOptions');
        if(playerOptions.microphoneEnabled())
        {
        //If microphone enabled, do nothing
        }
        else
        {
            playeroptions_open();
        }
    }
}

function playeroptions_open()
{
    $("#divPlayerOptions").dialog(
    {
        closeOnEscape: false,
        modal: true,
        width: 450,
        zIndex: 4000,
        buttons: [ {
            text:playeroptionsBtnOk, 
            click: function() {
                window.location.reload();
            }
        }],
        open: function(event, ui){
            $(".ui-dialog-titlebar-close", $(this).parent()).hide();
            $("#divPlayerOptions").css('position', 'relative');
            $("#divPlayerOptions").css('top',0);
        }
    });
}
                
//Run this function when player is ready
function playerrecorder_ready(idPlayer)
{
    if (playerRecorders[idPlayer] == undefined)
    {
        playerRecorders[idPlayer] = getFlashMovieObject(idPlayer);
    }
    if(this['playerrecorder_ready_'+idPlayer] != undefined)
    {
        this['playerrecorder_ready_'+idPlayer].apply(this,[]);
    }
}

//Init the connection with the player
function playerrecorder_ready_playerRecorderClassMonitor()
{
    playerRecorders['playerRecorderClassMonitor'].set_sPrefixFiles(files_prefix);
    playerRecorders['playerRecorderClassMonitor'].init_rtmpConnection(rtmpserver);
}

//Sent after connection established
function RTMPServerReady(idPlayer)
{
    $("#connectionScreen").fadeOut();
}

//This function refresh the history only if the data changed (based on a checksum)
function autoRefreshMonitor()
{
    $.getJSON(
        urlMonitor+"?activity_id="+activityid+"&rand="+Math.random(),
        function(data)
        {
            if(checksum == '' || checksum != data.checksum)
            {
                refreshStudentsList(data);
            }
            
            //Code for event hand raise
            for (i in data.hands)
            {
                addRaiseHandStudent(data.hands[i].data.studentid);
            }
            
            //add the call to the autorefresh
            setTimeout('autoRefreshMonitor()', secondsRefreshMonitor);
        }
        );
}

//Refresh the list of users
function refreshStudentsList(data)
{
    //update the checksum
    checksum = data.checksum;
    var jsonElem = null;
    var elem = null;
    for (i in data.json)
    {
        jsonElem = data.json[i];
        elem = $("#student_"+jsonElem.id);
        $(elem).data('liveURI', jsonElem.live);
        $(elem).data('userid', jsonElem.id);
        if(jsonElem.online)
        {
            if(elem.parent().id != 'listStudentsOnline')
            {
                elem.insertBefore($('#listStudentsOnline .clearfix'));
                addEventsToStudentElem(elem);
            }
        }
        else
        {
            if(elem.parent().id != 'listStudentsOffline')
            {
                elem.insertBefore($('#listStudentsOffline .clearfix'));
                removeEventsToStudentElem(elem);
            }
        }
    }
}

//Add the hover and click event to a student block
function addEventsToStudentElem(elem)
{
    //Remove the events
    $(elem).unbind('hover');
    $(elem).unbind('click');
    $('.talkToStudent', elem).unbind('click');
    $('.thumbsUp', elem).unbind('click');
    
    $(elem).hover(function()
    {
        //On hover Add class hover
        $(elem).addClass('classMonitorStudent_hover');
        //If an other student is clicked, do nothing
        if($(".classMonitorStudent_clicked").length == 0)
        {
            //Connect to the student live
            playerRecorders['playerRecorderClassMonitor'].resetNetStreams();
            playerRecorders['playerRecorderClassMonitor'].addURIToNetStreamsLive($(elem).data('liveURI'));
            
            //If not stealth
            if(!stealthActivated)
            {
                //Send Event to user
                $.getJSON(
                    urlMonitor+"?activity_id="+activityid
                    +"&event=listened_add&studentid="+$(elem).data('userid')
                    +"&rand="+Math.random(),
                    function(data)
                    {

                    });
            }
        }
    },function()
    {
        $(elem).removeClass('classMonitorStudent_hover');
        //Do something on the hover out
        if($(".classMonitorStudent_clicked").length == 0)
        {
            playerRecorders['playerRecorderClassMonitor'].resetNetStreams();
            
            //If not stealth
            if(!stealthActivated)
            {
                //Send Event to user
                $.getJSON(
                    urlMonitor+"?activity_id="+activityid
                    +"&event=listened_remove&studentid="+$(elem).data('userid')
                    +"&rand="+Math.random(),
                    function(data)
                    {

                    });
            }
        }
    });
    
    $(elem).click(function()
    {
        //Remove the potential raise hand
        removeRaiseHandStudent($(elem).data('userid'));
        
        if($(elem).hasClass('classMonitorStudent_clicked'))
        {
            $(elem).removeClass('classMonitorStudent_clicked');
            $('.menuStudent',elem).hide('blind', 250);
        }
        else
        {
            $(".classMonitorStudent.classMonitorStudent_clicked").click();
            $(elem).addClass('classMonitorStudent_clicked');
            $('.menuStudent',elem).show('blind', 250);
        }
    });
    //Do that to stop propagation of the click on parent element
    $('.menuStudent',elem).click(function(e){
        e.stopPropagation();
    });
    
    $('.talkToStudent', elem).click(function(){
        
        $("#dialogInfo").html(textLoadingConnectClient);
        $("#dialogInfo").dialog({
            modal: true,
            buttons: [], 
            open: function(event, ui){
                $(".ui-dialog-titlebar-close", $(this).parent()).hide();
            }
        });
        
    //Send Event to user
    $.getJSON(
        urlMonitor+"?activity_id="+activityid
        +"&event=live_add&studentid="+$(elem).data('userid')
        +"&teacherlive="+userLiveURI
        +"&rand="+Math.random(),
        function(data)
        {
            if(data.success)
            {
                $("#dialogInfo").text(data.message);
                $("#dialogInfo").dialog({
                    title: data.data.title,
                    resizable: false,
                    height:180,
                    closeOnEscape: false,
                    modal: true,
                    buttons: [
                    {
                        text: data.data.btnStop,
                        click: function() {
                            $("#dialogInfo").html(textLoadingDisconnectClient);
                            $.getJSON(
                                urlMonitor+"?activity_id="+activityid
                                +"&event=live_remove&studentid="+$(elem).data('userid')
                                +"&teacherlive="+userLiveURI
                                +"&rand="+Math.random(),
                                function(data)
                                {
                                    $("#dialogInfo").dialog( "close");
                                });
                            
                        }
                    }
                    ],
                    open: function(event, ui){
                        $(".ui-dialog-titlebar-close", $(this).parent()).hide();
                    }
                });
            }
            else
            {
                $("#dialogInfo").text(data.message);
                $("#dialogInfo").dialog({
                    title: data.message,
                    height:180,
                    buttons: []
                });
            }
        }); 
    });
    
$('.thumbsUp', elem).click(function(){
    //Send Event to user
    $.getJSON(
        urlMonitor+"?activity_id="+activityid
        +"&event=thumbs_up&studentid="+$(elem).data('userid')
        +"&rand="+Math.random(),
        function(data)
        {

        }); 
});
}

//Remove the events to a student block
function removeEventsToStudentElem(elem)
{
    if($(elem).hasClass('classMonitorStudent_clicked'))
    {
        $(elem).click();
    }
    $(elem).mouseleave();
    //Remove the events
    $(elem).unbind('hover');
    $(elem).unbind('click');
}

//Add a RaiseHand to a student
function addRaiseHandStudent(studentId)
{
    //Remove the potential hand raised
    removeRaiseHandStudent(studentId);
    
    var elem = $('#student_'+studentId);
    var inter = setInterval(function() {
        if (elem.hasClass('raiseHandStudent')) {
            elem.removeClass('raiseHandStudent');
        } else {
            elem.addClass('raiseHandStudent');
        }    
    }, 500);
    elem.data('raiseHandInterval', inter);
    
}

//Remove the RaiseHand for the student
function removeRaiseHandStudent(studentId)
{
    var elem = $('#student_'+studentId);
    clearInterval(elem.data('raiseHandInterval'));
    elem.removeClass('raiseHandStudent');
}

$(function(){

    $("#listStudentsOnline .classMonitorStudent").each(function(){
        addEventsToStudentElem(this);
    });
    
    
    //FILTER functionnality
    $("#searchStudents").keyup(function(){
        delay(function()
        {
            var patt=new RegExp($("#searchStudents").val(),'i');
            $(".studentName").each(function(){
                if($(this).text().search(patt) != -1)
                {
                    if($(this).parent().css('display') == 'none')
                    {
                        $(this).parent().show('highlight', {}, 500);
                    }
                }
                else
                {
                    if($(this).parent().css('display') != 'none')
                    {
                        //If the elem is selected, unselect it before
                        if($(this).parent().hasClass('classMonitorStudent_clicked'))
                        {
                            $(this).parent().click();
                        }
                        $(this).parent().mouseleave();
                        $(this).parent().fadeOut();
                    }
                }
            });
        }, 500);
    });
    $("#searchStudents").change(function(){
        $("#searchStudents").keyup();
    });
    
    $('#micConfig').click(function(){
        toggleDisplayOptions();
    });
    
    $("#stealth").click(function(){
        if(stealthActivated)
        {
            stealthActivated = false;
            $('.status', this).text(stealthTextInactive);
        }
        else
        {
            stealthActivated = true;
            $('.status', this).text(stealthTextActive);
        }
    });
    
    $("#speakToClass").click(function(){
        
        $("#dialogInfo").html(textLoadingConnectClient);
        $("#dialogInfo").dialog({
            modal: true,
            buttons: [], 
            open: function(event, ui){
                $(".ui-dialog-titlebar-close", $(this).parent()).hide();
            }
        });
        
    //Send Event to user
    $.getJSON(
        urlMonitor+"?activity_id="+activityid
        +"&event=liveclass_add&studentid=0"
        +"&teacherlive="+userLiveURI
        +"&rand="+Math.random(),
        function(data)
        {
            if(data.success)
            {
                $("#dialogInfo").text(data.message);
                $("#dialogInfo").dialog({
                    title: data.data.title,
                    resizable: false,
                    height:180,
                    closeOnEscape: false,
                    modal: true,
                    buttons: [
                    {
                        text: data.data.btnStop,
                        click: function() {
                            $("#dialogInfo").html(textLoadingDisconnectClient);
                            $.getJSON(
                                urlMonitor+"?activity_id="+activityid
                                +"&event=liveclass_remove&studentid=0"
                                +"&teacherlive="+userLiveURI
                                +"&rand="+Math.random(),
                                function(data)
                                {
                                    $("#dialogInfo").dialog( "close");
                                });
                            
                        }
                    }
                    ],
                    open: function(event, ui){
                        $(".ui-dialog-titlebar-close", $(this).parent()).hide();
                    }
                });
            }
            else
            {
                $("#dialogInfo").text(data.message);
                $("#dialogInfo").dialog({
                    title: data.message,
                    height:180,
                    buttons: []
                });
            }
        }); 
    });
    
    
    
if(typeof(secondsRefreshMonitor) != 'undefined')
{
    //SET TIMERS FOR UPDATE CONTENT OF THE Monitor
    autoRefreshMonitor();
}
});


//Used to add a delay to the typing of the search field
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();
